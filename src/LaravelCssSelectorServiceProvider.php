<?php

namespace BigBoca\LaravelCssSelector;

use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;
use ReflectionException;

class LaravelCssSelectorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     * @throws ReflectionException
     */
    public function register()
    {
        TestResponse::mixin(new LaravelCssSelectorMixin());
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
