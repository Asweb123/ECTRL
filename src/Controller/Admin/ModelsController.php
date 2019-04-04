<?php

namespace App\Controller\Admin;

use App\Entity\Certification;
use App\Entity\Theme;
use App\Form\AddThemeType;
use App\Form\EditThemeType;
use App\Form\ImportCsvType;
use App\Form\NewModelCertificationType;
use App\Repository\CertificationRepository;
use App\Repository\CompanyRepository;
use App\Repository\ThemeRepository;
use App\Service\CsvManager;
use App\Service\ModelManager;
use App\Service\ThemeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\MimeType\FileinfoMimeTypeGuesser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class ModelsController extends AbstractController
{
    /**
     * @Route("/admin/modeles", name="admin-modelList")
     */
    public function modelList(Request $request, CompanyRepository $companyRepository, ModelManager $modelManager)
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

        $modelCreationLeft = $modelManager->modelCreationLeft($company);

        return $this->render('admin/modelList.html.twig', [
            "company" => $company,
            "models" => $models,
            "modelCreationLeft" => $modelCreationLeft,
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/modele/{modelId}", name="admin-modelDetail")
     */
    public function modelDetail($modelId, CertificationRepository $certificationRepository, ThemeRepository $themeRepository)
    {
        $user = $this->getUser();
        $company = $user->getCompany();
        $model = $certificationRepository->find($modelId);

        if($model === null) {
            throw $this->createNotFoundException();
        }

        $themes = $themeRepository->findBy(['certification' => $model], ['rankCertification' => 'ASC']);

        return $this->render('admin/model.html.twig', [
            "model" => $model,
            "themes" => $themes,
            "company" => $company
        ]);
    }


    /**
     * @Route("/admin/model-modification/{modelId}", name="admin-editModel")
     */
    public function editModel(Request $request, $modelId, ThemeManager $themeManager, CertificationRepository $certificationRepository, ThemeRepository $themeRepository)
    {
        $user = $this->getUser();
        $company = $user->getCompany();
        $model = $certificationRepository->find($modelId);

        if($model === null) {
            throw $this->createNotFoundException();
        }

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

        $themes = $themeRepository->findBy(['certification' => $model], ['rankCertification' => 'ASC']);

        return $this->render('admin/editModel.html.twig', [
            "model" => $model,
            "themes" => $themes,
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

        if($model === null) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();

        if($model->getIsChild() === true){

            $em->remove($model);
            $em->flush();
        }

        return $this->redirectToRoute('admin-modelList', [
            "company" => $company
        ]);
    }

    /**
     * @Route("/admin/importation-csv", name="admin-importCsv")
     */
    public function importModel(Request $request, CsvManager $csvManager, ModelManager $modelManager)
    {
        $user = $this->getUser();
        $company = $user->getCompany();

        $modelCreationLeft = $modelManager->modelCreationLeft($company);

        if($modelCreationLeft <= 0){
            $this->redirectToRoute('admin-modelList');
        }

        $form = $this->createForm(ImportCsvType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $file = $form['file']->getData();

            $fileContent = file_get_contents($file);

            $return = $csvManager->csvToDbManager($fileContent, $company);

            if (is_string($return)) {
                $form->addError(new FormError($return));
            }
        }
        if ($form->isSubmitted() && $form->isValid()){
            return $this->redirectToRoute('admin-modelDetail', ["modelId" => $return->getId()]);
        }


        return $this->render('admin/importCsv.html.twig', [
            "company" => $company,
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/exemple-modele", name="admin-csvModel")
     */
    public function csvModel()
    {
        $root = $this->getParameter('kernel.project_dir');
        $response = new BinaryFileResponse($root.'/public/csv/exempleaudit.csv');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,'exempleaudit.csv');

        return $response;
    }

}
