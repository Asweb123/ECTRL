<?php

namespace App\Controller\Admin;

use App\Repository\AuditRepository;
use App\Service\ScoreAndProgManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin-dashboard")
     */
    public function index(
        AuditRepository $auditRepository,
        ScoreAndProgManager $scoreAndProgManager
    )
    {
        $user = $this->getUser();

        $company = $user->getCompany();
        $lastAudits = $auditRepository->findLastAudits($company);
        $averageLastAuditsScore = $scoreAndProgManager->averageLastAuditsScore($lastAudits);
        $recurrentRequirementList = $scoreAndProgManager->recurrentRequirementsFailed($company);

        return $this->render('admin/dashboard.html.twig', [
             "company" => $company,
             "lastAudits" => $lastAudits,
             "averageLastAuditsScore" => $averageLastAuditsScore,
             "recurrentRequirementList" => $recurrentRequirementList
        ]);
    }
}
