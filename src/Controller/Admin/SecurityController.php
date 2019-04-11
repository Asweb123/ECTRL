<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/home.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/mentions-legales", name="legals")
     */
    public function legal(): Response
    {
        return $this->render('admin/legals.html.twig');
    }

    /**
     * @Route("/app", name="app")
     */
    public function appAccess()
    {
        return $this->redirect('https://exp-shell-app-assets.s3.us-west-1.amazonaws.com/android/%40citrov/ectrl-6ec01bf929a642678ee8d93a48cec343-signed.apk');
    }
}
