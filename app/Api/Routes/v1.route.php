<?php
//api 版本自动继承
$V_NUM = 1;
$versions = getVersionInheritance('v' . $V_NUM);

$api->version($versions, ['middleware' => ['header.check'], 'namespace' => 'App\Api\Versions\v' . $V_NUM . '\Controllers'], function ($api) {
    // 用户 init
    $api->group(['prefix' => 'user'], function ($api) {
        $api->post('init', 'UserController@actionInit');
    });

    // 版本更新
    $api->get('version', 'VersionController@actionVersion')->name('appVersion');

});

