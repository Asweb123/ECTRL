<?php

namespace App\Controller\API\Authentication;


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

        dump($data);

        return new JsonResponse(
            [
                'status' => 'ok',
            ],
            JsonResponse::HTTP_OK
        );
    }
}
