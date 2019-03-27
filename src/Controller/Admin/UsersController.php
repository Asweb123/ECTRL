<?php

namespace App\Controller\Admin;

use App\Repository\RegisterCodeRepository;
use App\Repository\UserRepository;
use App\Service\RegisterCodeManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/admin/utilisateurs", name="admin-users")
     */
    public function userList(UserRepository $userRepository)
    {
        $user = $this->getUser();
        $company = $user->getCompany();
        $users = $userRepository->findBy(["isBanned" => false, "company" => $company], ["lastName" => 'ASC']);

        return $this->render('admin/userList.html.twig', [
            "company" => $company,
            "users" => $users
        ]);
    }

    /**
     * @Route("/admin/utilisateurs/{userId}", name="admin-deleteUser")
     */
    public function deleteUser($userId,
                               EntityManagerInterface $em,
                               UserRepository $userRepository,
                               RegisterCodeManager $registerCodeManager,
                               RegisterCodeRepository $registerCodeRepository)
    {
        $userToDelete = $userRepository->find($userId);

        if($userToDelete === null){
            throw $this->createNotFoundException();
        }

        $registerCode = $registerCodeRepository->find($userToDelete->getRegisterCode()->getId());
        $registerCodeManager->nbOfUsersRemover($registerCode, $userToDelete);
        $userToDelete->setIsBanned(true);

        $em->persist($userToDelete);
        $em->flush();

        return $this->redirectToRoute('admin-users');
    }
}
