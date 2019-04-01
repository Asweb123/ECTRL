<?php

namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    /**
     * @Route("/admin/notifications", name="admin-notifications")
     */
    public function userList()
    {
        $user = $this->getUser();
        $company = $user->getCompany();

        return $this->render('admin/notification.html.twig', [
            "company" => $company,
        ]);
    }

}
