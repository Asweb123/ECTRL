<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuditsController extends AbstractController
{
    /**
     * @Route("/admin/audits", name="admin-audits")
     */
    public function index()
    {
        return $this->render('admin/dashboard.html.twig', [

        ]);
    }
}
