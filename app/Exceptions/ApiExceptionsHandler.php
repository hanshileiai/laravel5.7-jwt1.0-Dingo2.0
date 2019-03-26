<?php

namespace App\Exceptions;

use App\Api\Versions\ApiResponseController;
use Exception;
use Dingo\Api\Exception\Handler as DingoHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiExceptionsHandler extends DingoHandler
{
    public function handle(Exception $exception)
    {

        $api = new ApiResponseController();
        if ($exception instanceof UnauthorizedHttpException) {
            return $api->responseTokenExpired();
        }

        return parent::handle($exception);
    }
}
