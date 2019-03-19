<?php
/**
 * Created by PhpStorm.
 * User: SIMON
 * Date: 19/03/2019
 * Time: 11:47
 */

namespace App\Controller\User;

use App\Form\GetUserType;
use App\Repository\AuditRepository;
use App\Repository\UserRepository;
use App\Service\ResponseManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserDataController extends AbstractController
{
    /**
     * @Route("/user/{uuidUser}", name="user", methods={"GET"})
     */
    public function getUserData($uuidUser, AuditRepository $auditRepository, ResponseManager $responseManager, UserRepository $userRepository)
    {
        try {

            $data = ['uuidUser' => $uuidUser];
            $form = $this->createForm(GetUserType::class);
            $form->submit($data);
            if($form->isValid() === false) {
                return $responseManager->response403(
                    403,
                    "wrong format value",
                    $form->getErrors(true)->getChildren()->getMessage()
                );
            }

            $user = $userRepository->find($uuidUser);

            if ($user === null) {
                return $responseManager->response404(
                    404,
                    "wrong uuid",
                    "L'utilisateur recherché n'existe pas."
                );
            }


            foreach ($user->getCompany()->getCertifications() as $certification){
                $certifications[] = [
                    "uuidCertification" => $certification->getId(),
                    "certificationTitle" => $certification->getTitle(),
                    "certificationDescription" => $certification->getDescription()
                ];
            }

            $audits = $auditRepository->findBy(['user' => $user], ['lastModificationDate' => 'DESC']);

            foreach ($audits as $audit){
                $auditList[] = [
                    "uuidAudit" => $certification->getId(),
                    "certificationTitle" => $audit->getCertification()->getTitle(),
                    "lastModification" => $audit->getLastModificationDate(),
                    "score" => $audit->getScore(),
                    "progression" => $audit->getProgression(),
                ];
            }

            return $responseManager->response200(
                200,
                "MAJ user data",
                "Données utilisateur mises à jour.",
                [
                    "firstName" => $user->getFirstName(),
                    "lastName" => $user->getLastName(),
                    "email" => $user->getEmail(),
                    "uuidUser" => $user->getId(),
                    "roleName" => $user->getRole()->getTitle(),
                    "rank" => $user->getRole()->getRank(),
                    "userSociety" => [
                        "uuidSociety" => $user->getCompany()->getId(),
                        "societyName" => $user->getCompany()->getName()
                    ],
                    "userEnable" => $user->getUserEnable(),
                    "certifications" => $certifications,
                    "audits" => $auditList
                ]
            );

        }

        catch(\Exception $ex){
            return $responseManager->response500();
        }

    }
}