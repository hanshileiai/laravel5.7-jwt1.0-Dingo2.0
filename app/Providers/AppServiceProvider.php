<?php

namespace App\Providers;

use App\Exceptions\ApiExceptionsHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 自定义 api 异常拦截
        $this->registerExceptionHandler();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach (glob(app_path('/Api/config/*.php')) as $path) {
            $path = realpath($path);
            $this->mergeConfigFrom($path, basename($path, '.php'));
        }

        // 引入自定义函数
        foreach (glob(app_path('/Helpers/*.php')) as $helper) {
            require_once $helper;
        }
        // 引入 api 版本路由
        $this->loadRoutesFrom(app_path('/Api/Routes/base.php'));

    }

    /**
     * Register the exception handler - extends the Dingo one
     *
     * @return void
     */
    protected function registerExceptionHandler()
    {
        $this->app->singleton('api.exception', function ($app) {
            return new ApiExceptionsHandler($app['Illuminate\Contracts\Debug\ExceptionHandler'], config('api.errorFormat'), config('api.debug'));
        });
    }

}
