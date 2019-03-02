<?php
/**
 * Created by PhpStorm.
 * User: SIMON
 * Date: 02/03/2019
 * Time: 20:55
 */

namespace App\Controller\API\Authentication;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AuthenticationController extends AbstractController
{
    /**
     * @Route("/api/register", methods={POST})
     */
    public function register(Request $request, Response $response)
    {

    }
}