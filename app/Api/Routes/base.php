<?php

use Dingo\Api\Routing\Router;

$api = app(Router::class);

/*
 * 新版的路由
 */
// 所有路由文件
$routePath = glob(api_path('Routes/*.route.php'));
// 自然排序，按版本顺序加载路由
natcasesort($routePath);

foreach ($routePath as $route) {
    require $route;
}
