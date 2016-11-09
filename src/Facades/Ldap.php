<?php
namespace Aimme\Ldap\Facades;

use Illuminate\Support\Facades\Facade;

class Ldap extends Facade
{
    protected static function getFacadeAccessor() { 
        return 'ldap';
    }

        /**
     * Register the typical authentication routes for an application.
     *
     * @return void
     */
    public static function routes()
    {
        static::$app->make('router')->auth();
    }
}