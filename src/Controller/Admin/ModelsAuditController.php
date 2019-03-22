<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ModelsAuditController extends AbstractController
{
    /**
     * @Route("/admin/modele-d'audit", name="admin-models-audits")
     */
    public function index()
    {
        return $this->render('admin/dashboard.html.twig', [

        ]);
    }
}
