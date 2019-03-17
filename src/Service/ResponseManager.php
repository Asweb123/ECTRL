<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseManager
{
    public function response200($code, $details, $message, $result)
    {
        if(count($result) === 0){
            $result = json_encode($result, JSON_FORCE_OBJECT);
        }

        return new JsonResponse(
            [
                "code"=> $code,
                "details" => $details,
                "message" => $message,
                "result" => $result
            ],
            JsonResponse::HTTP_OK
        );
    }

    public function response403($code, $details, $message)
    {
        return new JsonResponse(
            [
                "code"=> $code,
                "details" => $details,
                "message" => $message,
                "result" => json_encode([], JSON_FORCE_OBJECT)
            ],
            JsonResponse::HTTP_FORBIDDEN
        );
    }

    public function response404($code, $details, $message)
    {
        return new JsonResponse(
            [
                "code"=> $code,
                "details" => $details,
                "message" => $message,
                "result" => json_encode([], JSON_FORCE_OBJECT)
            ],
            JsonResponse::HTTP_NOT_FOUND
        );
    }

    public function response500()
    {
        return new JsonResponse(
            [
                "code"=> 500,
                "details" => "Error server",
                "message" => "Serveur indisponible. Réessayez dans quelques instants.",
                "result" => json_encode([], JSON_FORCE_OBJECT)
            ],
            JsonResponse::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
