<?php

namespace App\Rules;

interface RuleInterface
{
    /**
     * Register a validation rule.
     *
     * @return void
     */
    public static function register();
}