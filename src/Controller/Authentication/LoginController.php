<?php

namespace App\Controller\Authentication;


use App\Entity\User;
use App\Form\UserLoginType;
use App\Form\UserRegisterType;
use App\Repository\RegisterCodeRepository;
use App\Repository\UserRepository;
use App\Service\RegisterCodeManager;
use App\Service\ResponseManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class LoginController extends AbstractController
{

    private $em;
    private $userRepository;
    private $registerCodeRepository;
    private $userPasswordEncoder;
    private $responseManager;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        RegisterCodeRepository $registerCodeRepository,
        UserPasswordEncoderInterface $userPasswordEncoder,
        ResponseManager $responseManager
    ){
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->registerCodeRepository = $registerCodeRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->responseManager = $responseManager;
    }


    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $form = $this->createForm(UserLoginType::class);
            $form->submit($data);
            if($form->isValid() === false) {
                return $this->responseManager->response403(
                    460,
                    "wrong format values",
                    $form->getErrors(true)->getChildren()->getMessage()
                );
            }

            $user = $this->userRepository->findOneBy(['email' => $data["email"]]);

            if (($user === null) || ($this->userPasswordEncoder->isPasswordValid($user, $data["password"]) === false)) {
                return $this->responseManager->response403(
                    403,
                    "wrong credentials",
                    "Identifiants incorrects."
                );
            }

            if ($user->getUserEnable() === false) {
                return $this->responseManager->response403(
                    461,
                    "Register user without account activation by mail",
                    "Vous devez activer votre compte avant de pouvoir vous connecter à l'application."
                );
            }

            $certifications = [];
            foreach ($user->getCompany()->getCertifications() as $certification){
                $certifications[] = [
                    "uuidCertification" => $certification->getId(),
                    "certificationTitle" => $certification->getTitle(),
                    "certificationDescription" => $certification->getDescription()
                ];
            }



            return $this->responseManager->response200(
               200,
               "The user can access to the app.",
               "Connexion ok",
                [
                    "firstName" => $user->getFirstName(),
                    "lastName" => $user->getLastName(),
                    "email" => $user->getEmail(),
                    "uuidUser" => $user->getId(),
                    "roleName" => $user->getRole()->getTitle(),
                    "userSociety" => [
                        "uuidSociety" => $user->getCompany()->getId(),
                        "societyName" => $user->getCompany()->getName()
                    ],
                    "userEnable" => $user->getUserEnable(),
                    "certifications" => $certifications
                ]
            );

        }

        catch(\Exception $ex){
            return $this->responseManager->response500();
        }

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
