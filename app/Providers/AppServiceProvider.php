<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 添加自定义验证规则,允许字母数字和 - _(中文还存在问题)
        Validator::extend('allow_letter', function ($attribute, $value, $parameters, $validator) {
            return is_string($value) && preg_match('/^[A-Za-z0-9_\x{4e00}-\x{9fa5}]+$/u', $value);
        });
        // 添加自定义验证规则,金额正则表达式最多两位小数，只允许正数
        Validator::extend('amount', function ($attribute, $value, $parameters, $validator) {
            return is_string($value) && preg_match('/^(?!0+(?:\.0+)?$)(?:[1-9]\d*|0)(?:\.\d{1,2})?$/', $value);
        });
        //电话号码
        Validator::extend('phone_num', function ($attribute, $value, $parameters, $validator) {
            return is_string($value) && preg_match('/^1(?:3\d|4[4-9]|5[0-35-9]|6[67]|7[013-8]|8\d|9\d)\d{8}$/', $value);
        });
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
