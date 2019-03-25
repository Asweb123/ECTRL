<?php

namespace App\Controller\Admin;

use App\Repository\AuditRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuditsController extends AbstractController
{
    /**
     * @Route("/admin/audits", name="admin-auditList")
     */
    public function auditList(Request $request, AuditRepository $auditRepository, PaginatorInterface $paginator)
    {
        $user = $this->getUser();

        $company = $user->getCompany();
        $auditListQuery = $auditRepository->findAuditList($company, 'DESC');

        $pagination = $paginator->paginate(
            $auditListQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            7 /*limit per page*/
        );

        return $this->render('admin/auditList.html.twig', [
            "company" => $company,
            "pagination" => $pagination
        ]);
    }

    /**
     * @Route("/admin/audit/{auditId}", name="admin-auditDetail")
     */
    public function auditDetail($auditId, AuditRepository $auditRepository)
    {
        $audit = $auditRepository->find($auditId);
        $company = $audit->getCompany();
        $requirements = $audit->getCertification()->getRequirements();


        return $this->render('admin/audit.html.twig', [
            "audit" => $audit,
            "company" => $company,
            "requirements" => $requirements
        ]);
    }
}
