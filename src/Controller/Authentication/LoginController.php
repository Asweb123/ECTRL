<?php

namespace App\Controller\Authentication;


use App\Form\ForgotPassType;
use App\Form\ResetPassType;
use App\Form\UserLoginType;
use App\Repository\AuditRepository;
use App\Repository\RegisterCodeRepository;
use App\Repository\UserRepository;
use App\Service\ResponseManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function login(Request $request, AuditRepository $auditRepository)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $form = $this->createForm(UserLoginType::class);
            $form->submit($data);
            if($form->isValid() === false) {
                return $this->responseManager->response403(
                    403,
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
                    403,
                    "Register user without account activation by mail",
                    "Vous devez activer votre compte avant de pouvoir vous connecter à l'application."
                );
            }

            if ($user->getIsBanned() === true) {
                return $this->responseManager->response403(
                    403,
                    "user banned by the company.",
                    "Vous ne possédez plus des droits nécessaires pour accéder à l'application."
                );
            }

            foreach ($user->getCompany()->getCertifications() as $certification){
                $certifications[] = [
                    "uuidCertification" => $certification->getId(),
                    "certificationTitle" => $certification->getTitle(),
                    "certificationDescription" => $certification->getDescription()
                ];
            }

            $audits = $auditRepository->findBy(['user' => $user], ['lastModificationDate' => 'DESC']);

            $auditList = [];
            foreach ($audits as $audit){
                $auditList[] = [
                    "uuidAudit" => $audit->getId(),
                    "certificationTitle" => $audit->getCertification()->getTitle(),
                    "lastModification" => $audit->getLastModificationDate(),
                    "score" => $audit->getScore().'%',
                    "auditStatus" => $audit->getStatus(),
                    "auditCreatedAt" => $audit->getCreationDate(),
                    "progression" => $audit->getProgression().'%',
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
                    "hasToken" => $user->getHasToken(),
                    "rank" => $user->getRole()->getRank(),
                    "userSociety" => [
                        "uuidSociety" => $user->getCompany()->getId(),
                        "societyName" => $user->getCompany()->getName()
                    ],
                    "userEnable" => $user->getUserEnable(),
                    "certifications" => $certifications,
                    "audits" => $auditList
                ]
            );

        }

        catch(\Exception $ex){
            dump($ex);
            return $this->responseManager->response500();
        }

    }


    /**
     * @Route("/forgotPassword", name="forgotPassword", methods={"POST"})
     */
    public function forgotPassword(Request $request, \Swift_Mailer $mailer)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $form = $this->createForm(ForgotPassType::class);
            $form->submit($data);
            if($form->isValid() === false) {
                return $this->responseManager->response403(
                    403,
                    "Wrong format values.",
                    $form->getErrors(true)->getChildren()->getMessage()
                );
            }

            $user = $this->userRepository->findOneBy(['email' => $data["email"]]);
            $registerCode = $this->registerCodeRepository->findOneBy(['codeContent' => $data["code"]]);

            if ($user === null || $registerCode === null || $user->getRegisterCode() !== $registerCode) {
                return $this->responseManager->response403(
                    403,
                    "Wrong crédentials.",
                    "Identifiants invalides."
                );
            }

            $message = (new \Swift_Message('Mot de passe oublié - eCtrl'))
                ->setFrom('ectrl.service@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/resetPassword.html.twig',
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
                "Right credentials for the reset password process",
                "Veuillez consulter le mail envoyé à l’adresse renseignée pour créer votre compte.",
                ["uuidUser" => $user->getId()]
            );
        }

        catch(\Exception $ex){
            return $this->responseManager->response500();
        }
    }

    /**
     * @Route("/resetPassword/{userId}", name="resetPassword")
     */
    public function resetPassword(Request $request, $userId)
    {
        $user = $this->userRepository->find($userId);

        if($user === null){
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ResetPassType::class);
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
