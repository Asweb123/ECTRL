<?php
/**
 * Created by PhpStorm.
 * User: SIMON
 * Date: 27/03/2019
 * Time: 21:40
 */

namespace App\Controller\Admin;

use App\Entity\Requirement;
use App\Form\DeleteRequirementType;
use App\Form\PostRequirementType;
use App\Form\PutRequirementType;
use App\Repository\RequirementRepository;
use App\Repository\ThemeRepository;
use App\Service\RequirementManager;
use App\Service\ThemeManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RequirementAPIController extends AbstractController
{
    private $em;
    private $requirementRepository;
    private $themeRepository;
    private $requirementManager;

    public function __construct(EntityManagerInterface $em, RequirementRepository $requirementRepository, ThemeRepository $themeRepository, RequirementManager $requirementManager)
    {
        $this->em = $em;
        $this->requirementRepository = $requirementRepository;
        $this->themeRepository = $themeRepository;
        $this->requirementManager = $requirementManager;
    }

    /**
     * @Route("/admin/{themeId}/exigences", name="admin-getRequirements", methods={"GET"})
     */
    public function getRequirements($themeId)
    {
        try{
            $theme = $this->themeRepository->find($themeId);

            If($theme === null){
                return new JsonResponse(null,JsonResponse::HTTP_NOT_FOUND);
            }

            $data = $this->requirementManager->getRequirementsManager($theme);

            return new JsonResponse($data,JsonResponse::HTTP_OK);

        } catch (\Exception $ex){

            return new JsonResponse(null, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @Route("/admin/{themeId}/exigences", name="admin-postRequirements", methods={"POST"})
     */
    public function postRequirement(Request $request, $themeId)
    {
        try{
            $data = $request->request->get('requirement');
            $formData = ["requirement" => $data];

            $theme = $this->themeRepository->find($themeId);

            If($theme === null){
                return new JsonResponse(null,JsonResponse::HTTP_NOT_FOUND);
            }

            $form = $this->createForm(PostRequirementType::class);
            $form->submit($formData);
            if($form->isValid() === false) {
                return new JsonResponse(null,JsonResponse::HTTP_FORBIDDEN);
            }

            $model = $theme->getCertification();
            $newRequirement = new Requirement();

            $newRequirement = $this->requirementManager->postRequirementManager($newRequirement, $theme);
            $newRequirement->setDescription($data);

            $model->addRequirement($newRequirement);
            $theme->addRequirement($newRequirement);

            $this->em->persist($newRequirement);
            $this->em->persist($model);
            $this->em->persist($theme);

            $this->em->flush();

            $newRequirement = $this->requirementRepository->findOneBy(["theme" => $theme], ['rankTheme' => 'DESC']);

            $preparedData = $this->requirementManager->postReturnData($newRequirement);

            return new JsonResponse($preparedData,JsonResponse::HTTP_OK);

        } catch (\Exception $ex){

            return new JsonResponse(null, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @Route("/admin/{themeId}/exigences", name="admin-putRequirements", methods={"PUT"})
     */
    public function putRequirement(Request $request, $themeId)
    {
        try{
            $uuidData = $request->request->get('uuid');
            $requirementData = $request->request->get('requirement');
            $formData = ["uuid" => $uuidData, "requirement" => $requirementData];

            $theme = $this->themeRepository->find($themeId);

            If($theme === null){
                return new JsonResponse(null,JsonResponse::HTTP_NOT_FOUND);
            }

            $form = $this->createForm(PutRequirementType::class);
            $form->submit($formData);
            if($form->isValid() === false) {
                return new JsonResponse(null,JsonResponse::HTTP_FORBIDDEN);
            }

            $requirementToUpdate = $this->requirementRepository->find($uuidData);

            If($requirementToUpdate === null){
                return new JsonResponse(null,JsonResponse::HTTP_NOT_FOUND);
            }

            $requirementToUpdate->setDescription($requirementData);

            $this->em->persist($requirementToUpdate);
            $this->em->flush();

            $preparedData = $this->requirementManager->putReturnData($requirementToUpdate);

            return new JsonResponse($preparedData,JsonResponse::HTTP_OK);

        } catch (\Exception $ex){

            return new JsonResponse(null, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @Route("/admin/{themeId}/exigences", name="admin-deleteRequirements", methods={"DELETE"})
     */
    public function deleteRequirement(Request $request, $themeId)
    {
        try{
            $uuidData = $request->request->get('uuid');
            $formData = ["uuid" => $uuidData];

            $theme = $this->themeRepository->find($themeId);

            If($theme === null){
                return new JsonResponse(null,JsonResponse::HTTP_NOT_FOUND);
            }

            $form = $this->createForm(DeleteRequirementType::class);
            $form->submit($formData);
            if($form->isValid() === false) {
                return new JsonResponse(null,JsonResponse::HTTP_FORBIDDEN);
            }

            $requirement = $this->requirementRepository->find($uuidData);

            if($requirement === null){
                return new JsonResponse(null,JsonResponse::HTTP_NOT_FOUND);
            }

            $this->requirementManager->deleteRequirementManager($requirement);

            return new JsonResponse(null,JsonResponse::HTTP_OK);

        } catch (\Exception $ex){

            return new JsonResponse(null, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}