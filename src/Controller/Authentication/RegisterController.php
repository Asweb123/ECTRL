<?php

namespace App\Controller\Authentication;

use App\Entity\User;
use App\Form\RegisterCodeType;
use App\Form\UserRegisterType;
use App\Service\RegisterCodeManager;
use App\Repository\RegisterCodeRepository;
use App\Repository\UserRepository;
use App\Service\ResponseManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegisterController extends AbstractController
{

    private $em;
    private $userRepository;
    private $registerCodeRepository;
    private $responseManager;
    private $registerCodeManager;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        RegisterCodeRepository $registerCodeRepository,
        ResponseManager $responseManager,
        RegisterCodeManager $registerCodeManager
    ){
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->registerCodeRepository = $registerCodeRepository;
        $this->responseManager = $responseManager;
        $this->registerCodeManager = $registerCodeManager;
    }

    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, \Swift_Mailer $mailer, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $formUser = $this->createForm(UserRegisterType::class, new User());
            $formUser->submit($data);
            if ($formUser->isValid() === false) {
                return $this->responseManager->response403(
                    403,
                    "Wrong users values",
                    $formUser->getErrors(true)->getChildren()->getMessage()
                );
            }

            $formCode = $this->createForm(RegisterCodeType::class);
            $formCode->submit($data);
            if($formCode->isValid() === false) {
                return $this->responseManager->response403(
                    403,
                    "Wrong register code value",
                    $formCode->getErrors(true)->getChildren()->getMessage()
                );
            }

            $registerCode = $this->registerCodeRepository->findOneBy(['codeContent' => $data["code"]]);

            if ($registerCode === null) {
                return $this->responseManager->response403(
                    403,
                    "Wrong register code value",
                    "Le code d'enregistrement renseigné n'existe pas."
                );
            }

            if ($this->registerCodeManager->NbOfUsersChecker($registerCode) === false) {
                return $this->responseManager->response403(
                    403,
                    "Max number of accounts for this register code is reached.",
                    "Le nombre maximum d'utilisateurs pour ce code d'enregistrement est déjà atteint."
                );
            }


            $user = new User();
            $user->setEmail($data['email']);
            $user->setFirstName($data['firstName']);
            $user->setLastName($data["lastName"]);
            $user->setPassword($userPasswordEncoder->encodePassword($user, $data["password"]));
            $user->setCompany($registerCode->getCompany());
            $user->setRole($registerCode->getRole());
            $user->setRegisterCode($registerCode);
            $this->em->persist($user);
            $this->em->flush();

            $user = $this->userRepository->findOneBy(['email' => $data["email"]]);

            $message = (new \Swift_Message('Activez votre compte eCtrl'))
                ->setFrom('ectrl.service@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/accountActivation.html.twig',
                        [
                            'userId' => $user->getId(),
                            'firstName' => $user->getFirstName(),
                            'lastName' => $user->getlastName()
                        ]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            return $this->responseManager->response200(
                200,
                "Register done.",
                "Enregistrement effectué.",
                ["email" => $user->getEmail()]
            );
        }

        catch (\Exception $ex){
            return $this->responseManager->response500();
        }

    }


    /**
     * @Route("/register/{userId}", name="registerConfirmation")
     */
    public function RegisterConfirmation($userId, RegisterCodeManager $registerCodeManager)
    {

        $user = $this->userRepository->find($userId);

        if($user === null){
            throw $this->createNotFoundException();
        }

        $registerCode = $user->getRegisterCode();

        if($registerCodeManager->NbOfUsersChecker($registerCode) === false){
            return $this->render('authentication/usersNbReached.html.twig');
        }

        if($user->getUserEnable() === true){
            return $this->render('authentication/registerConfirmation.html.twig');
        }

        if($registerCodeManager->NbOfUsersChecker($registerCode) === true){

            $registerCodeManager->NbOfUsersUpdater($registerCode, $user);
            $user->setUserEnable(true);
            $this->em->persist($user);
            $this->em->flush();
        }

        return $this->render('authentication/registerConfirmation.html.twig');
    }

}
