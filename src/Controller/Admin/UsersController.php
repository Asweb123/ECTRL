<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/admin/utilisateurs", name="admin-users")
     */
    public function index()
    {
        return $this->render('admin/dashboard.html.twig', [

        ]);
    }
}
