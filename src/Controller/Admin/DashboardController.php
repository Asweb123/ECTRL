<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin-dashboard")
     */
    public function index()
    {


        return $this->render('admin/dashboard.html.twig', [
           // "company" => $company,
           // "lastAudits" => $lastAudits,
           // "requirementsMostFailed" => $requirementsMostFailed

        ]);
    }
}
