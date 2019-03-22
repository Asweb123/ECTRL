<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NotificationsController extends AbstractController
{
    /**
     * @Route("/admin/notifications", name="admin-notifications")
     */
    public function index()
    {
        return $this->render('admin/dashboard.html.twig', [

        ]);
    }
}
