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

     //   $data = $request->getContent();


        $data = $data[0];


        $receivedCode = $data["code"];
        $repository = $this->getDoctrine()->getrepository(RegisterCode::class);
        $registerCode = $repository->findOneBy(['codeContent' => $receivedCode]);

        if ($registerCode === null) {
            return new JsonResponse(
                [
                    "code"=> 403,
                    "details" => "Wrong registerCode",
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
                    "details" => "Wrong register user values",
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
        $user->setUserCompany($registerCode->getRegisterCodeCompany());
        $user->setUserRole($registerCode->getRegisterCodeRole());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

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
                        "uuidSociety" => $user->getUserCompany()->getId(),
                        "societyName" => $user->getUserCompany()->getCompanyName()
                    ]
                ]
            ],
            JsonResponse::HTTP_OK
        );
    }
}
