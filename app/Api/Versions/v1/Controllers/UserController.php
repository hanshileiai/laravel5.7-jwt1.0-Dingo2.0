<?php

namespace App\Api\Versions\v1\Controllers;

use App\Api\Traits\TokenManager;
use App\Api\Traits\UserManager;
use Illuminate\Http\Request;
use App\Api\Versions\BaseController;

class UserController extends BaseController
{
    use UserManager;
    use TokenManager;

    /**
     * 初始化.
     *
     * @param Request $request
     *
     * @return array
     */
    public function actionInit(Request $request)
    {
        $userId = $request->header(config('my.headers.user_id'));
        $user = $this->UserModel()->where('uuid', '=', $userId)->first();
        if(!$user){
            $userId = $this->makeUserId();
            //创建用户
            $this->createUser($request, $userId);
        }
        // 查找用户
        $user = $this->UserModel()->where('uuid', '=', $userId)->first();

        // token ttl 过期时间
        $expires_in = $this->getCurrentTokenTtl($user);
        // 如果 ttl <= 1天，重新登录，更换新 token
        if($expires_in>0){
            // 使用老 token
            $token = $this->getCurrentToken($user->uuid);
        }else{
            $token = $this->createTokenFromUser($user);
            $this->updateToken($user->uuid, $token);
            $this->guard()->login($user);
            $expires_in = $this->guard()->factory()->getTTL() * 60;
        }

        $data = [
            'user_id' => $userId,
            'token' => $token,
            'expires_in' => $this->getCurrentTokenTtl($user),
        ];

        return $this->responseSuccess($data);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::guard()->user());
    }
}
