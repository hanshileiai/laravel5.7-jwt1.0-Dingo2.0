<?php

function api_path($path)
{
   return  app_path('Api') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
}

/**
 * api 版本自动继承
 * User: shilei
 * @param string $currentVersion
 * @return array
 */
function getVersionInheritance($currentVersion = 'v1')
{
    $start = (int)str_replace('v','',$currentVersion);

    $versions = [];
    $vPaths = glob(api_path('Versions/v*'));
    // 自然排序
    natcasesort($vPaths);

    // 取得所有版本号
    foreach ($vPaths as $path){
        $v = basename($path);
        if((int)str_ireplace('v','',$v) >= $start){
            $versions[] = strtolower($v);
        }
    }

    return $versions;
}