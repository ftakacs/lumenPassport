<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

class FullName implements RuleInterface
{
    /**
     * Register a validation rule.
     *
     * @return void
     */
    public static function register()
    {
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
        },'You must enter the Full Name');
    }
}