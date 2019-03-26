<?php

namespace App\Api\Versions;


class BaseController extends ApiResponseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['actionInit'] ]);
    }
}