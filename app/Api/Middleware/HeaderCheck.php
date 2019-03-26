<?php

namespace App\Api\Middleware;

use App\Api\Versions\ApiResponseController;
use Closure;
use Illuminate\Http\Request;

class HeaderCheck
{
    public function handle(Request $request, Closure $next)
    {
        $api = new ApiResponseController();

        // accept 格式验证
        $accept = $request->header('accept');
        if (!preg_match('/application\/vnd\.app\.v(\d+)\+json/i', $accept)) {
            return $api->responseLoginException($api::RESPONSE_MESSAGES['HEADER_ERROR']);
        }

        // 存在 X-PR-Id , X-PR-Basic 验证
        $userIdKey = config('my.headers.user_id');
        $idSignKey = config('my.headers.id_sign');
        if (!$request->hasHeader($userIdKey) || !$request->hasHeader($idSignKey)) {
            return $api->responseLoginException($api::RESPONSE_MESSAGES['HEADER_ERROR']);
        }

        // X-PR-Id , X-PR-Basic base64 验证
        $userId = $request->header($userIdKey);
        $idSign = $request->header($idSignKey);
        if (!compare_header($userId, $idSign)) {
            return $api->responseLoginException($api::RESPONSE_MESSAGES['NOT_EQ_UUID_BASIC']);
        }

        return $next($request);
    }
}
