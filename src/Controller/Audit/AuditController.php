<?php
/**
 * Created by PhpStorm.
 * User: SIMON
 * Date: 14/03/2019
 * Time: 13:19
 */

namespace App\Controller\Audit;


use App\Entity\Audit;
use App\Entity\Result;
use App\Form\DeleteAuditType;
use App\Form\EditAuditType;
use App\Form\GetAuditType;
use App\Form\NewAuditType;
use App\Repository\AuditRepository;
use App\Repository\CertificationRepository;
use App\Repository\RequirementRepository;
use App\Repository\ResultRepository;
use App\Repository\ThemeRepository;
use App\Repository\UserRepository;
use App\Service\ResponseManager;
use App\Service\ScoreAndProgManager;
use App\Service\SendNotificationManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AuditController extends AbstractController
{
    private $em;
    private $userRepository;
    private $responseManager;
    private $certificationRepository;
    private $auditRepository;
    private $requirementRepository;
    private $themeRepository;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        CertificationRepository $certificationRepository,
        ResponseManager $responseManager,
        AuditRepository $auditRepository,
        RequirementRepository $requirementRepository,
        ThemeRepository $themeRepository
    ){
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->certificationRepository = $certificationRepository;
        $this->responseManager = $responseManager;
        $this->auditRepository = $auditRepository;
        $this->requirementRepository = $requirementRepository;
        $this->themeRepository = $themeRepository;
    }


    /**
     * @Route("/audit", name="newAudit", methods={"POST"})
     */
    public function newAudit(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $form = $this->createForm(NewAuditType::class);
            $form->submit($data);
            if($form->isValid() === false) {
                return $this->responseManager->response403(
                    403,
                    "wrong format values",
                    $form->getErrors(true)->getChildren()->getMessage()
                );
            }

            $user = $this->userRepository->find($data['uuidUser']);
            $certification = $this->certificationRepository->find($data['uuidCertification']);

            if(($user === null) || ($user->getCompany()->getCertifications()->contains($certification) === false)){
                return $this->responseManager->response403(
                    403,
                    "Wrong user or certification relation",
                    "L’utilisateur n’existe pas ou son entreprise n’a pas les droits pour réaliser un audit sur cette certification."
                );
            }

            $audit = new Audit();
            $audit->setCertification($certification);
            $audit->setUser($user);
            $audit->setCompany($user->getCompany());
            $this->em->persist($audit);

            $requirementList = $this->requirementRepository->findBy(['certification' => $certification], ['rankCertification' => 'ASC']);
            foreach($requirementList as $requirement){
                $result = new Result();
                $result->setAudit($audit);
                $result->setRequirement($requirement);
                $this->em->persist($result);

                $requirements[] = [
                    "uuidRequirement" => $requirement->getId(),
                    "themeTitle" => $requirement->getTheme()->getTitle(),
                    "themeDescription" => $requirement->getTheme()->getDescription(),
                    "themeRankCertification" => $requirement->getTheme()->getRankCertification(),
                    "themeColor" => $requirement->getTheme()->getColor(),
                    "requirementDescription" => $requirement->getDescription(),
                    "requirementRankTheme" => $requirement->getRankTheme(),
                    "requirementRankCertification" => $requirement->getRankCertification(),
                    "uuidResult" => $result->getId(),
                    "state" => $result->getState()
                ];
            }

            $certificationThemeList = $this->themeRepository->findBy(['certification' => $certification], ['rankCertification' => 'ASC']);
            foreach ($certificationThemeList as $key => $theme){
                $totalAndLeftRequirementsNb = count($theme->getRequirements());
                $metadata[] = [
                    "requirementsT".($key+1)."Nb" => $totalAndLeftRequirementsNb,
                    "requirementsT".($key+1)."LeftNb" => $totalAndLeftRequirementsNb
                ];
            }

            $this->em->flush();

            return $this->responseManager->response200(
                200,
                "New audit created.",
                "Nouvelle grille d'audit générée.",
                [
                    "uuidUser" => $user->getId(),
                    "certificationsTitle" => $certification->getTitle(),
                    "uuidAudit" => $audit->getId(),
                    "requirementsLeft" => $requirements,
                    "metadata" => $metadata
                ]
            );
        }

        catch(\Exception $ex){
            return $this->responseManager->response500();
        }

    }


    /**
     * @Route("/audit/{uuidAudit}", name="getAudit", methods={"GET"})
     */
    public function getAudit($uuidAudit,  ResultRepository $resultRepository)
    {
        try {

            $data['uuidAudit'] = $uuidAudit;
            $form = $this->createForm(GetAuditType::class);
            $form->submit($data);
            if($form->isValid() === false) {
                return $this->responseManager->response403(
                    403,
                    "wrong format values",
                    $form->getErrors(true)->getChildren()->getMessage()
                );
            }

            $audit = $this->auditRepository->find($uuidAudit);

            if($audit === null){
                return $this->responseManager->response404(
                    404,
                    "No audit for the given id",
                    "L’audit recherché n’existe pas."
                );
            }

            foreach($audit->getCertification()->getRequirements() as $requirement){
                $result = $resultRepository->findOneBy(['audit' => $audit, 'requirement' => $requirement]);
                if($result->getState() === 0 || $result->getState() === 2){
                  $requirementsLeft[] = [
                        "uuidRequirement" => $requirement->getId(),
                        "themeTitle" => $requirement->getTheme()->getTitle(),
                        "themeDescription" => $requirement->getTheme()->getDescription(),
                        "themeRankCertification" => $requirement->getTheme()->getRankCertification(),
                        "themeColor" => $requirement->getTheme()->getColor(),
                        "requirementDescription" => $requirement->getDescription(),
                        "requirementRankTheme" => $requirement->getRankTheme(),
                        "requirementRankCertification" => $requirement->getRankCertification(),
                        "uuidResult" => $result->getId(),
                        "state" => $result->getState(),
                        ];
                 }
             }

            $certificationThemeList = $this->themeRepository->findBy(['certification' => $audit->getCertification()], ['rankCertification' => 'ASC']);
            foreach ($certificationThemeList as $key => $theme){
                $totalRequirementsNb = count($theme->getRequirements());
                $requirementsLeftNb = 0;
                foreach($theme->getRequirements() as $requirement){
                    $result = $resultRepository->findOneBy(['audit' => $audit, 'requirement' => $requirement]);
                    if($result->getState() === 0 || $result->getState() === 2){
                        $requirementsLeftNb ++;
                    }
                }
                $metadata[] = [
                    "requirementsT".($key+1)."Nb" => $totalRequirementsNb,
                    "requirementsT".($key+1)."LeftNb" => $requirementsLeftNb
                ];
            }

            return $this->responseManager->response200(
                200,
                "Audit requested.",
                "Audit demandé.",
                [
                    "uuidUser" => $audit->getUser()->getId(),
                    "uuidCertification" => $audit->getCertification()->getId(),
                    "certificationsTitle" => $audit->getCertification()->getTitle(),
                    "uuidAudit" => $audit->getId(),
                    "requirementsLeft" => $requirementsLeft,
                    "metadata" => $metadata
                ]
            );
        }

        catch(\Exception $ex){
            return $this->responseManager->response500();
        }

    }


    /**
     * @Route("/audit", name="editAudit", methods={"PATCH"})
     */
    public function EditAudit(Request $request, ResultRepository $resultRepository, ScoreAndProgManager $scoreAndProgManager, SendNotificationManager $sendNotificationManager)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $form = $this->createForm(EditAuditType::class);
            $form->submit($data);
            if($form->isValid() === false) {
                return $this->responseManager->response403(
                    403,
                    "wrong format values",
                    $form->getErrors(true)->getChildren()->getMessage()
                );
            }

            if(((int)$data['state'] < 1) || ((int)$data['state'] > 3)){
                return $this->responseManager->response403(
                    403,
                    "wrong format values",
                    "Format de réponse invalide."
                );
            }

            $currentResult = $resultRepository->find($data['uuidResult']);
            if($currentResult === null){
                return $this->responseManager->response403(
                    403,
                    "Wrong id for this result",
                    "L'identifiant pour cette réponse n'est pas correct."
                );
            }

            $currentResult->setState($data['state']);
            $this->em->persist($currentResult);
            $this->em->flush();

            $audit = $currentResult->getAudit();
            $audit->setLastModificationDate(new \Datetime('now'));

            $progressionRaw = 0;
            foreach($audit->getResults() as $result){
                if($result->getState() === 1 || $result->getState() === 3){
                    $progressionRaw++;
                }
            }

            $progression = $scoreAndProgManager->perCentCalculator($progressionRaw, count($audit->getResults()));
            $audit->setProgression($progression);

            if($progression >= 100){
                $score = $scoreAndProgManager->scoreCalculator($audit->getCertification()->getRequirements(), $audit);
                $audit->setScore($score);
                $audit->setStatus(2);
               // $user = $result->getAudit()->getUser();
               // $sendNotificationManager->sendNotification($user);

            }

            $this->em->persist($audit);
            $this->em->flush();

            return $this->responseManager->response200(
                200,
                "Requirement result saved.",
                "Le resultat pour cette exigence à bien été sauvegardé.",
                [
                    "uuidUser" => $audit->getUser()->getId(),
                    "certificationsTitle" => $audit->getCertification()->getTitle(),
                    "uuidAudit" => $audit->getId(),
                ]
            );
        }

        catch(\Exception $ex){
            return $this->responseManager->response500();
        }

    }

    /**
     * @Route("/audit", name="deleteAudit", methods={"DELETE"})
     */
    public function deleteAudit(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $form = $this->createForm(DeleteAuditType::class);
            $form->submit($data);
            if($form->isValid() === false) {
                return $this->responseManager->response403(
                    403,
                    "wrong format values",
                    $form->getErrors(true)->getChildren()->getMessage()
                );
            }

            $audit = $this->auditRepository->find($data['uuidAudit']);

            if($audit === null){
                return $this->responseManager->response404(
                    404,
                    "No audit for the given id",
                    "L’audit recherché n’existe pas."
                );
            }

            $this->em->remove($audit);
            $this->em->flush();

            return $this->responseManager->response200(
                200,
                "Audit deleted.",
                "Audit supprimé.",
                []
            );
        }

        catch(\Exception $ex){
            return $this->responseManager->response500();
        }

    }

}
