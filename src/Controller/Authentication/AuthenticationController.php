<?php

namespace App\Controller\Authentication;


use App\Entity\User;
use App\Form\UserRegisterType;
use App\Repository\RegisterCodeRepository;
use App\Repository\UserRepository;
use App\Service\RegisterCodeManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AuthenticationController extends AbstractController
{

    private $em;
    private $userRepository;
    private $registerCodeRepository;
    private $userPasswordEncoder;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        RegisterCodeRepository $registerCodeRepository,
        UserPasswordEncoderInterface $userPasswordEncoder
    ){
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->registerCodeRepository = $registerCodeRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, \Swift_Mailer $mailer, RegisterCodeManager $registerCodeManager)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $registerCode = $this->registerCodeRepository->findOneBy(['codeContent' => $data["code"]]);

            if ($registerCode === false) {
                return new JsonResponse(
                    [
                        "code"=> 403,
                        "details" => "Wrong register code values",
                        "result" => []
                    ],
                    JsonResponse::HTTP_FORBIDDEN
                );
            }

            if ($registerCodeManager->NbOfUsersChecker($registerCode) === false) {
                return new JsonResponse(
                    [
                        "code"=> 403,
                        "details" => "Max number of accounts for this register code is reached.",
                        "result" => []
                    ],
                    JsonResponse::HTTP_FORBIDDEN
                );
            }

            $formUser = $this->createForm(UserRegisterType::class, new User());
            $formUser->submit($data);

            if ($formUser->isValid() === false) {
                return new JsonResponse(
                    [
                        "code"=> 403,
                        "details" => "Wrong register values",
                        "result" => []
                    ],
                    JsonResponse::HTTP_FORBIDDEN
                );
            }

            $user = new User();
            $user->setEmail($data['email']);
            $user->setFirstName($data['firstName']);
            $user->setLastName($data["lastName"]);
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, $data["password"]));
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

            return new JsonResponse(
                [
                    "code" => 200,
                    "details" => "Register done",
                    "result" => [
                        "firstName" => $user->getFirstName(),
                        "lastName" => $user->getLastName(),
                        "email" => $user->getEmail(),
                        "uuidUser" => $user->getId(),
                        "userSociety" => [
                          "uuidSociety" => $user->getCompany()->getId(),
                          "societyName" => $user->getCompany()->getName()
                       ]
                  ]
                ],
                JsonResponse::HTTP_OK
          );

        }

        catch (\Exception $ex){
            return new JsonResponse(
                [
                    "code" => 500,
                    "détails" => "Error server",
                    "result" => []
                ]
            );
        }

    }


    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $user = $this->userRepository->findOneBy(['email' => $data["email"]]);

            if (($user === null) || ($this->userPasswordEncoder->isPasswordValid($user, $data["password"]) === false)) {
                return new JsonResponse(
                    [
                        "code"=> 403,
                        "details" => "Wrong login values",
                        "result" => []
                    ],
                    JsonResponse::HTTP_FORBIDDEN
                );
            }

    //        if ($user->getUserEnable() === false) {
    //            return new JsonResponse(
    //                [
    //                    "code"=> 403,
    //                    "details" => "register user without account activation by mail",
    //                    "result" => []
    //                ],
    //                JsonResponse::HTTP_FORBIDDEN
    //            );
    //        }
            
            return new JsonResponse(
                [
                    "code" => 200,
                    "details" => "The user can access to the app.",
                    "result" => [
                        "firstName" => $user->getFirstName(),
                        "lastName" => $user->getLastName(),
                        "email" => $user->getEmail(),
                        "uuidUser" => $user->getId(),
                        "userRole" => [
                            "uuidRole" => $user->getRole()->getId(),
                            "roleName" => $user->getRole()->getTitle()
                        ],
                        "userSociety" => [
                            "uuidSociety" => $user->getCompany()->getId(),
                            "societyName" => $user->getCompany()->getName()
                        ],
                        "userEnable" => $user->getUserEnable(),
                        "userAware" => $user->getUserAware()
                    ]
                ],
                JsonResponse::HTTP_OK
            );
        }

        catch(\Exception $ex){
            return new JsonResponse(
                [
                    "code" => 500,
                    "details" => "server error",
                    "results" => []
                ]
            );
        }

    }


    /**
     * @Route("/register/{userId}", name="registerConfirmation")
     */
    public function RegisterConfirmation($userId, RegisterCodeManager $registerCodeManager)
    {

        $user = $this->userRepository->find($userId);
        $registerCode = $user->getRegisterCode()->getCodeContent();

        if($registerCodeManager->NbOfUsersChecker($registerCode) === false){
            return $this->render('authentication/usersNbReached.html.twig');
        }

        if($registerCodeManager->NbOfUsersChecker($registerCode) === true){

            $registerCodeManager->NbOfUsersUpdater($registerCode, $user);

            $user->setUserEnable(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('authentication/registerConfirmation.html.twig');
    }


    /**
     * @Route("/forgotPassword", name="forgotPassword", methods={"POST"})
     */
    public function forgotPasswordRequest(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $user = $this->userRepository->findOneBy(['email' => $data["email"]]);
            $registerCode = $this->registerCodeRepository->findOneBy(['codeContent' => $data["codeContent"]]);

            if ($user === null || $user->getRegisterCode() !== $registerCode) {
                return new JsonResponse(
                    [
                        "code"=> 403,
                        "details" => "wrong values",
                        "result" => []
                    ],
                    JsonResponse::HTTP_FORBIDDEN
                );
            }

            return new JsonResponse(
                [
                    "code" => 200,
                    "details" => "The user exist for this email and register code",
                    "result" => [
                        "uuidUser" => $user->getId(),
                    ]
                ],
                JsonResponse::HTTP_OK
            );
        }

        catch(\Exception $ex){
            return new JsonResponse(
                [
                    "code" => 500,
                    "details" => "server error",
                    "results" => []
                ]
            );
        }
    }


    /**
     * @Route("/changePassword", name="changePassword", methods={"POST"})
     */
    public function changePasswordRequest(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $user = $this->userRepository->find([$data["uuid"]]);

            if ($user === null || $data === null) {
                return new JsonResponse(
                    [
                        "code"=> 403,
                        "details" => "wrong values",
                        "result" => []
                    ],
                    JsonResponse::HTTP_FORBIDDEN
                );
            }

            $password = $this->userPasswordEncoder->encodePassword($user, $data['password']);
            $user->setPassword($password);

            return new JsonResponse(
                [
                    "code" => 200,
                    "details" => "user password change success",
                    "result" => [
                        "firstName" => $user->getFirstName(),
                        "lastName" => $user->getLastName(),
                        "email" => $user->getEmail(),
                        "uuidUser" => $user->getId(),
                        "userRole" => [
                            "uuidRole" => $user->getRole()->getId(),
                            "roleName" => $user->getRole()->getTitle()
                        ],
                        "userSociety" => [
                            "uuidSociety" => $user->getCompany()->getId(),
                            "societyName" => $user->getCompany()->getName()
                        ],
                        "userEnable" => $user->getUserEnable(),
                        "userAware" => $user->getUserAware()
                    ]
                ],
                JsonResponse::HTTP_OK
            );
        }

        catch(\Exception $ex){
            return new JsonResponse(
                [
                    "code" => 500,
                    "details" => "server error",
                    "results" => []
                ]
            );
        }
    }

}
