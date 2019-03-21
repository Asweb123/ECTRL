<?php
/**
 * Created by PhpStorm.
 * User: SIMON
 * Date: 21/03/2019
 * Time: 11:54
 */

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class LoginBOController extends AbstractController
{
    /**
     * @Route("/", name="loginBO")
     */
    public function IndexLoginBO(Request $request)
    {
        $form = $this->createForm(LoginBOType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, $data['password']));

            $this->em->persist($user);
            $this->em->flush();

            return $this->render('authentication/modifiedPassword.html.twig');
        }

        return $this->render('authentication/modifyPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}