<?php

namespace App\Controller\API\Authentication;


use App\Entity\RegisterCode;
use App\Entity\User;
use App\Form\UserRegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AuthenticationController extends AbstractController
{

    /**
     * @Route("/api/register", name="register", methods={"POST"})
     */
    public function register(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $repository = $this->getDoctrine()->getrepository(RegisterCode::class);
        $registerCode = $repository->findOneBy(['codeContent' => $data["code"]]);

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
        $user->setPassword($data["password"]);
        $user->setCompany($registerCode->getCompany());
        $user->setRole($registerCode->getRole());

    //    $entityManager = $this->getDoctrine()->getManager();
    //    $entityManager->persist($user);
    //    $entityManager->flush();

        return new JsonResponse(
            [
                "code" => "200",
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


    /**
     * @Route("/api/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {
        $data = json_decode($request->getContent(), true);
       
        $repository = $this->getDoctrine()->getrepository(User::class);
        $user = $repository->findOneBy(['email' => $data["email"]]);


        if (($user === null) || ($user->getPassword() !== $data["password"])) {
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
                "code" => "200",
                "details" => "The user can access to your super app dude!",
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
                    ]
                ]
            ],
            JsonResponse::HTTP_OK
        );
    }

}
