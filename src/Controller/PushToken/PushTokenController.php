<?php
/**
 * Created by PhpStorm.
 * User: SIMON
 * Date: 13/03/2019
 * Time: 12:38
 */

namespace App\Controller\PushToken;


use App\Entity\ExpoPushToken;
use App\Form\ExpoPushTokenType;
use App\Repository\UserRepository;
use App\Service\ResponseManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PushTokenController extends AbstractController
{
    /**
     * @Route("/expoPushToken", name="expoPushToken", methods={"POST"})
     */
    public function saveExpoPushToken(Request $request, UserRepository $userRepository, ResponseManager $responseManager, EntityManagerInterface $em)
    {
        try {

            $data = json_decode($request->getContent(), true);

            $form = $this->createForm(ExpoPushTokenType::class);
            $form->submit($data);
            if($form->isValid() === false) {
                return $responseManager->response403(
                    403,
                    "wrong format values",
                    $form->getErrors(true)->getChildren()->getMessage()
                );
            }

            $user = $userRepository->find($data['uuidUser']);
            if($user === null){
                return $responseManager->response404(
                    404,
                    "No user for the given id",
                    "L’utilisateur n’existe pas."
                );
            }

            $expoPushToken = new ExpoPushToken();
            $expoPushToken->setUser($user);
            $expoPushToken->setToken($data['expoPushToken']);

            $user->setHasToken(true);
            $em->persist($user);
            $em->persist($expoPushToken);
            $em->flush();

            return $responseManager->response200(
                200,
                "pushToken saved",
                "ExpoPushToken enregistré.",
                []
            );

        }

        catch(\Exception $ex){
            dump($ex);
            return $responseManager->response500();
        }

    }

}