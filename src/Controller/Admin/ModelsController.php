<?php

namespace App\Controller\Admin;

use App\Entity\Certification;
use App\Entity\Theme;
use App\Form\AddThemeType;
use App\Form\NewModelCertificationType;
use App\Repository\CertificationRepository;
use App\Repository\CompanyRepository;
use App\Service\ThemeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModelsController extends AbstractController
{
    /**
     * @Route("/admin/modeles", name="admin-modelList")
     */
    public function modelList(CompanyRepository $companyRepository)
    {
        $user = $this->getUser();
        $company = $companyRepository->find($user->getCompany()->getId());
        $models = $company->getCertifications();

        return $this->render('admin/modelList.html.twig', [
            "company" => $company,
            "models" => $models
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
            "company" => $company,

        ]);
    }


    /**
     * @Route("/admin/model-creation", name="admin-createModel")
     */
    public function NewModel(Request $request)
    {
        $user = $this->getUser();
        $company = $user->getCompany();

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
        return $this->render('admin/modelNew.html.twig', [
            "company" => $company,
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/model-modification/{modelId}", name="admin-editModel")
     */
    public function EditModel(Request $request, $modelId, ThemeManager $themeManager, CertificationRepository $certificationRepository)
    {
        $user = $this->getUser();
        $company = $user->getCompany();
        $model = $certificationRepository->find($modelId);

        $theme = new Theme();
        $form = $this->createForm(AddThemeType::class, $theme);

        $theme = $themeManager->Ranker($theme, $model);
        $theme = $themeManager->colorSetter($theme, $theme->getRankCertification());
        $theme->setCertification($model);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $model->addTheme($theme);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($theme);
            $entityManager->persist($model);
            $entityManager->flush();

            return $this->redirectToRoute('admin-editModel', [
                "modelId" => $model->getId(),
                "model" => $model,
                "company" => $company,
                "form" => $form->createView()
            ]);
        }

        return $this->render('admin/editModel.html.twig', [
            "model" => $model,
            "company" => $company,
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/model-suppression-theme/{themeId}", name="admin-deleteTheme")
     */
    public function addTheme(Request $request, $modelId, CertificationRepository $certificationRepository, ThemeManager $themeManager)
    {
        if($request->isXmlHttpRequest()){
            $model = $certificationRepository->find($modelId);

            $theme = new Theme();
            $theme = $themeManager->Ranker($theme, $model);
            $theme = $themeManager->colorSetter($theme, $theme->getRankCertification());
            $theme->setCertification($model);

            $form = $this->createForm(AddThemeType::class, $theme);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $model->addTheme($theme);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($theme);
                $entityManager->persist($model);
                $entityManager->flush();
                dump($theme);
                return $this->render('admin/modelNew.html.twig', [
                    "company" => $company,
                    "form" => $form->createView()
                ]);
            }
        }

        return $this->render('admin/modelNew.html.twig', [
            "company" => $company,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/model-suppression/{modelId}", name="admin-deleteModel")
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
