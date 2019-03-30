<?php

namespace App\Controller\Admin;

use App\Entity\Certification;
use App\Entity\Theme;
use App\Form\AddThemeType;
use App\Form\EditThemeType;
use App\Form\NewModelCertificationType;
use App\Repository\CertificationRepository;
use App\Repository\CompanyRepository;
use App\Repository\ThemeRepository;
use App\Service\ThemeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModelsController extends AbstractController
{
    /**
     * @Route("/admin/modeles", name="admin-modelList")
     */
    public function modelList(Request $request, CompanyRepository $companyRepository)
    {
        $user = $this->getUser();
        $company = $companyRepository->find($user->getCompany()->getId());
        $models = $company->getCertifications();

        $certification = new Certification();

        $form = $this->createForm(NewModelCertificationType::class, $certification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $certification->addCompany($company);
            $certification->setIsChild(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($certification);
            $entityManager->flush();

            return $this->redirectToRoute('admin-editModel', ["modelId" => $certification->getId()]);
        }

        return $this->render('admin/modelList.html.twig', [
            "company" => $company,
            "models" => $models,
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/modele/{modelId}", name="admin-modelDetail")
     */
    public function modelDetail($modelId, CertificationRepository $certificationRepository)
    {
        $user = $this->getUser();
        $company = $user->getCompany();
        $model = $certificationRepository->find($modelId);

        if($model === null) {
            throw $this->createNotFoundException();
        }

        return $this->render('admin/model.html.twig', [
            "model" => $model,
            "company" => $company
        ]);
    }


//    /**
//     * @Route("/admin/model-creation", name="admin-createModel")
//     */
//    public function NewModel(Request $request)
//    {
//        $user = $this->getUser();
//        $company = $user->getCompany();
//
//        $certification = new Certification();
//
//        $form = $this->createForm(NewModelCertificationType::class, $certification);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $certification->addCompany($company);
//            $certification->setIsChild(true);
//
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($certification);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('admin-editModel', ["modelId" => $certification->getId()]);
//        }
//        return $this->render('admin/modelNew.html.twig', [
//            "company" => $company,
//            "form" => $form->createView()
//        ]);
//    }


    /**
     * @Route("/admin/model-modification/{modelId}", name="admin-editModel")
     */
    public function editModel(Request $request, $modelId, ThemeManager $themeManager, CertificationRepository $certificationRepository, ThemeRepository $themeRepository)
    {
        $user = $this->getUser();
        $company = $user->getCompany();
        $model = $certificationRepository->find($modelId);

        $newTheme = new Theme();

        $formAddTheme = $this->createForm(AddThemeType::class, $newTheme, ["method" => "POST"]);


        $newTheme = $themeManager->Ranker($newTheme, $model);
        $newTheme = $themeManager->colorSetter($newTheme, $newTheme->getRankCertification());
        $newTheme->setCertification($model);

        $formAddTheme->handleRequest($request);


        if ($formAddTheme->isSubmitted() && $formAddTheme->isValid()) {

            $model->addTheme($newTheme);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newTheme);
            $entityManager->persist($model);
            $entityManager->flush();

            return $this->redirectToRoute('admin-editModel', ["modelId" => $model->getId()]);
        }

        $formEditTheme = $this->createForm(EditThemeType::class, null, ["method" => "PUT"]);
        $formEditTheme->handleRequest($request);

        if ($formEditTheme->isSubmitted() && $formEditTheme->isValid()) {
            $data = $formEditTheme->getData();

            $editTheme = $themeRepository->find($data["id"]);

            if($editTheme === null) {
                return $this->createNotFoundException();
            }

            $editTheme->setTitle($data["title"]);
            $editTheme->setDescription($data["description"]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($editTheme);
            $entityManager->flush();

            return $this->redirectToRoute('admin-editModel', ["modelId" => $model->getId()]);
        }

        if(count($model->getThemes()) !== 0){
            foreach($model->getThemes() as $key => $theme){
                $formEditThemeList[$key] = $formEditTheme->createView();
            }
        } else {
            $formEditThemeList[0] = $formEditTheme->createView();
        }

        return $this->render('admin/editModel.html.twig', [
            "model" => $model,
            "company" => $company,
            "formAddTheme" => $formAddTheme->createView(),
            "formEditTheme" => $formEditThemeList

        ]);
    }


    /**
     * @Route("/admin/suppression-theme/{themeId}", name="admin-deleteTheme")
     */
    public function deleteTheme($themeId, ThemeManager $themeManager, ThemeRepository $themeRepository)
    {

        $theme = $themeRepository->find($themeId);
        if($theme === null){
            return $this->createNotFoundException();
        }

        $model = $theme->getCertification();

        $themeManager->deleteThemeManager($theme);

        return $this->redirectToRoute('admin-editModel', ["modelId" => $model->getId()]);
    }

    /**
     * @Route("/admin/suppression-model/{modelId}", name="admin-deleteModel")
     */
    public function DeleteModel($modelId, CertificationRepository $certificationRepository)
    {
        $user = $this->getUser();
        $company = $user->getCompany();

        $model = $certificationRepository->find($modelId);

        $em = $this->getDoctrine()->getManager();

        if($model->getIsChild() === true){

            $em->remove($model);
            $em->flush();
        }

        return $this->redirectToRoute('admin-modelList', [
            "company" => $company
        ]);
    }
}
