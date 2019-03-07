<?php

namespace App\Controller\Authentication;


use App\Entity\RegisterCode;
use App\Entity\User;
use App\Form\UserRegisterType;
use App\Repository\RegisterCodeRepository;
use App\Repository\UserRepository;
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
    public function register(Request $request, \Swift_Mailer $mailer)
    {
        try {
            $data = json_decode($request->getContent(), true);

    //        $repository = $this->getDoctrine()->getRepository(RegisterCode::class);
     //       $registerCode = $repository->findOneBy(['codeContent' => $data["code"]]);
            $registerCode = $this->registerCodeRepository->findOneBy(['codeContent' => $data["code"]]);

            $formUser = $this->createForm(UserRegisterType::class, new User());
            $formUser->submit($data);

            if (($registerCode === null) || ($formUser->isValid() === false)) {
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
            dump($ex);
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
                        "userEnable" => $user->getUserEnable()
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
    public function RegisterConfirmation($userId)
    {
    //    $repository = $this->getDoctrine()->getRepository(User::class);
    //    $user = $repository->find($userId);
        $user = $this->userRepository->find($userId);

        if($user !== null){
            $user->setUserEnable(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('authentication/registerConfirmation.html.twig');
    }
}
