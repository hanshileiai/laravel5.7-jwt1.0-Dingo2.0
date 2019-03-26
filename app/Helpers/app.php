<?php

function compare_header(string $userId, string $idSign): bool
{
    $idSignSha1 = base64_decode($idSign);
    $userIdSha1 = sha1($userId);

    return $idSignSha1 == $userIdSha1 ? true : false;
}

/**
 * 获取请求中携带的token
 * @return mixed
 */
function parse_token()
{
    return app('tymon.jwt.parser')->parseToken();
}

if (!function_exists('getApiCurrentRequestVersion')) {
    /**
     * 获取当前 api 请求 int 数字版本号
     * @return int
     */
    function getApiCurrentRequestVersion()
    {
        $version = 5;
        // $apiVersion = 'application/vnd.app.v8+json';
        $apiVersion = request()->header('Accept');
        preg_match('/application\/vnd\.app\.v(\d+)\+json/', $apiVersion, $match);
        if (isset($match[1])) {
            $version = $match[1];
        }

        return $version;
    }
}


if (!function_exists('isAppTypeAndroid')){
    function isAppTypeAndroid($ua){
        if (strpos(strtolower($ua), 'android')){
            return true;
        }else{
            return false;
        }
    }
}

if (!function_exists('isAppTypeIOS')){
    function isAppTypeIOS($ua){
        if (strpos(strtolower($ua), 'ios')){
            return true;
        }else{
            return false;
        }
    }
}
