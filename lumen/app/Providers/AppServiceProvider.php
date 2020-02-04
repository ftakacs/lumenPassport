<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dusterio\LumenPassport\LumenPassport;
use App\Rules\FullName;
use App\Rules\Cpf;

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
        FullName::register();
        Cpf::register();
    }
}
