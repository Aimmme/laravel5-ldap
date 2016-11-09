[![Build Status](https://travis-ci.org/Aimmme/laravel5-ldap.svg?branch=master)](https://travis-ci.org/Aimmme/laravel-ldap)
![Built for Laravel 5](https://img.shields.io/badge/Built_for-Laravel-red.svg?style=flat-square)
[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/Aimmme/laravel-ldap/blob/master/LICENSE)

* Tested only on Laravel 5.3
* You will need to have user Eloquent Model, and the database users table should have email field
* You could use both default Auth and Ldap
* Ldap facade works as Auth. Its the gate for ldap authentication
* Users should be in the providers (in this case users) table and Active Directory, password field is not required.
* See below to configure provider

####Installation

1 - Require this package with composer:

    composer require aimme/laravel5-ldap


2 - add provider

    file: config/app.php

    'providers' => [
        ....
        Aimme\Ldap\LdapServiceProvider::class,
    ];

3 - add alias

    file: config/app.php

    'aliases' => [
        ....
        'Ldap' => Aimme\Ldap\Facades\Ldap::class,
        ....
    ];
    
4 - run these artisan commands

    php artisan make:ldap-auth
    
    php artisan vendor:publish --provider="Aimme\Ldap\LdapServiceProvider"

5 - add  middleware

    file: app/Http/Kernel.php

    protected $routeMiddleware = [
        ...
        'ldap' => \Aimme\Ldap\Middleware\Authenticate::class,
        ...
    ];


6 - bring following configuration changes to the ldap config file

file: config/ldap.php

- change providers array users model to your Eloquent user model path if it's different


    ~~~~
    'providers' => [
        'users' => [
            'driver' => 'ldap',
            'model' => App\User::class,
        ],
    ~~~~

- set your environment variables. 
    
    ~~~~
    'domain' => env('LDAP_DOMAIN', 'MYDOMAIN'),
    'host' => env('LDAP_HOST', 'ldap://aimme.mydomain.net'),
    ~~~~

See http://php.net/manual/en/function.ldap-connect.php
- host(LDAP_HOST) is the $host eg: ldap://aimme.mydomain.net

See http://php.net/manual/en/function.ldap-bind.php 
- domain(LDAP_DOMAIN) is the $bind_rdn example: MYDOMAIN





