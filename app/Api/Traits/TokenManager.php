<?php

namespace App\Api\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Facades\JWTAuth;

trait TokenManager
{
    public $tags = ['app', 'tokens'];

    public function buildCacheKey($uuid)
    {
        $prefix = (defined('IS_WEB_REQUEST') && IS_WEB_REQUEST) ? 'web_' : '';
        return $prefix . 'cache' . $uuid;
    }

    public function updateToken($uuid, $token)
    {
        //清除老的
        $this->removeOldToken($uuid);
        $cacheKey = $this->buildCacheKey($uuid);
        Cache::tags($this->tags)->forever($cacheKey, $token);
    }

    public function getCurrentToken($uuid)
    {
        $cacheKey = $this->buildCacheKey($uuid);

        return Cache::tags($this->tags)->has($cacheKey) ? Cache::tags($this->tags)->get($cacheKey) : '';
    }

    public function removeOldToken($uuid)
    {
        try {
            $oldToken = $this->getCurrentToken($uuid);
            !empty($oldToken) && JWTAuth::setToken($oldToken)->invalidate();
        } catch (\Exception $e) {}
    }

    public function getCurrentTokenTtl($user)
    {
        if(!$user){
            return 0;
        }

        $token = $this->getCurrentToken($user->uuid);
        if(empty($token)) return 0;
        try{
            $payLoad = app('tymon.jwt')->setToken($token)->getPayload();
            $exp = $payLoad->get('exp');
            $diffSeconds = Carbon::now()->diffInSeconds(carbon::createFromTimestamp($exp), true);
        }catch (\Exception $e){
            return 0;
        }

        return $diffSeconds;
    }
}
