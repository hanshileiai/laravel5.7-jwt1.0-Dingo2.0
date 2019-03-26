<?php
//api 版本自动继承
$V_NUM = 2;
$versions = getVersionInheritance('v' . $V_NUM);

$api->version($versions, ['middleware' => ['header.check'], 'namespace' => 'App\Api\Versions\v' . $V_NUM . '\Controllers'], function ($api) {

});

