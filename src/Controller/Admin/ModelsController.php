<?php

namespace App\Controller\Admin;

use App\Entity\Certification;
use App\Form\NewModelCertificationType;
use App\Repository\AuditRepository;
use App\Repository\CertificationRepository;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $certification->addCompany($company);

        $form = $this->createForm(NewModelCertificationType::class, $certification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
    public function EditModel($modelId, CertificationRepository $certificationRepository)
    {
        $user = $this->getUser();
        $company = $user->getCompany();
        $model = $certificationRepository->find($modelId);

        return $this->render('admin/editModel.html.twig', [
            "model" => $model,
            "company" => $company
        ]);
    }

    /**
     * @Route("/admin/model-sauvegarde/{modelId}", name="admin-saveModel")
     */
    public function SaveModel(AuditRepository $auditRepository)
    {
        $user = $this->getUser();

        $company = $user->getCompany();

        return $this->render('admin/createModel.html.twig', [

            "company" => $company
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
        $em->remove($model);
        $em->flush();

        return $this->redirectToRoute('admin-modelList');
    }
}
