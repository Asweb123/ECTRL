<?php
/**
 * Created by PhpStorm.
 * User: SIMON
 * Date: 13/03/2019
 * Time: 13:01
 */

namespace App\Controller\Sidebar;

use App\Form\ChangePassType;
use App\Form\DeleteUserType;
use App\Repository\RegisterCodeRepository;
use App\Repository\UserRepository;
use App\Service\ResponseManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{
    private $em;
    private $userRepository;
    private $userPasswordEncoder;
    private $responseManager;
    private $registerCodeRepository;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $userPasswordEncoder,
        ResponseManager $responseManager,
        RegisterCodeRepository $registerCodeRepository

    ){
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->registerCodeRepository = $registerCodeRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->responseManager = $responseManager;
    }

   /**
    * @Route("/changePassword", name="changePassword", methods={"POST"})
    */
   public function modifyPasswordRequest(Request $request)
   {
       try {
           $data = json_decode($request->getContent(), true);

           $form = $this->createForm(ChangePassType::class);
           $form->submit($data);
           if($form->isValid() === false) {
               return $this->responseManager->response403(
                   403,
                   "Wrong password format",
                    $form->getErrors(true)->getChildren()->getMessage()
               );
           }

           $user = $this->userRepository->find($data["uuidUser"]);
           if ($user === null || $this->userPasswordEncoder->isPasswordValid($user, $data["oldPassword"]) === false) {
               return $this->responseManager->response403(
                   403,
                   "Wrong credentials",
                   "Ancien mot de passe incorrect"
               );
           }

           $password = $this->userPasswordEncoder->encodePassword($user, $data['newPassword']);
           $user->setPassword($password);
           $this->em->persist($user);
           $this->em->flush();

           return $this->responseManager->response200(
               200,
               "Password changed.",
               "Votre nouveau mot de passe a bien été enregistré.",
               []
           );
       }

       catch(\Exception $ex){
           return $this->responseManager->response500();
       }
   }


    /**
     * @Route("/deleteUser", name="deleteUser", methods={"POST"})
     */
    public function deleteUser(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $form = $this->createForm(DeleteUserType::class);
            $form->submit($data);
            if($form->isValid() === false) {
                return $this->responseManager->response403(
                    403,
                    "Wrong password format",
                    $form->getErrors(true)->getChildren()->getMessage()
                );

            }

            $user = $this->userRepository->find($data["uuidUser"]);
            if ($user === null || $this->userPasswordEncoder->isPasswordValid($user, $data["password"]) === false) {
                return $this->responseManager->response403(
                    403,
                    "Wrong credentials",
                    "Mot de passe incorrect"
                );
            }

            $this->em->remove($user);
            $this->em->flush();

            return $this->responseManager->response200(
                200,
                "User deleted.",
                "Votre compte a bien été supprimé.",
                []
            );
        }

        catch(\Exception $ex){
            return $this->responseManager->response500();
        }
    }
}