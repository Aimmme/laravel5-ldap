<?php

return [
    'domain' => env('LDAP_DOMAIN', 'MYDOMAIN'),
    'host' => env('LDAP_HOST', 'ldap://aimme.mydomain.net'),
    /*
    |--------------------------------------------------------------------------
    | Fileds
    |--------------------------------------------------------------------------
    |
    | See attributes parameter - http://php.net/manual/en/function.ldap-search.php
    | attributes
    | An array of the required attributes, e.g. array("mail", "sn", "cn"). 
    | Note that the "dn" is always returned irrespective of which  attributes types are requested.
    | Using this parameter is much more efficient than the default action 
    | (which is to return all attributes and their associated values). 
    | The use of this parameter should therefore be considered good practice.
    |
    */
    'fields' => [
        "samaccountname", "mail", "displayname"
    ],
    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],
    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'ldap',
            'model' => App\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],
];