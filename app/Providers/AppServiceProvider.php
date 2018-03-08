<?php

namespace App\Providers;

use Event;
use App\Messages;
use App\Events\MessageCreated;
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
        Messages::created(function ($messages) {
            Event::fire(new MessageCreated($messages));
        });
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
