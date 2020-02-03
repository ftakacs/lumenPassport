<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Dusterio\LumenPassport\LumenPassport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        LumenPassport::routes($this->app);
        Validator::extend('fullName', function($attribute, $value, $parameters) {
            $fullName = trim($value);
            $fullName = preg_replace('/\s+/', ' ', $fullName);
            $names = explode(' ',$fullName);
            if(count($names) < 2){
                return false;
            }
            foreach ($names as $name) {
                if(strlen($name) < 2){
                    return false;
                }
            }
            return true;
        });
    }
}
