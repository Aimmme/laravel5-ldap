<?php
namespace Aimme\Ldap;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Aimme\Ldap\MakeLdapAuthCommand;
use Aimme\Ldap\AuthManager;

class LdapServiceProvider extends ServiceProvider
{
    protected $commands = [
        MakeLdapAuthCommand::class,
    ];

    public function boot()
    {
        $this->publishes([
        __DIR__.'/config.php' => config_path('ldap.php'),
        ]);
    }

    public function register()
    {
        $this->commands($this->commands);

        $this->registerAliases();

        $this->registerAuthenticator();

        $this->registerUserResolver();

        $this->registerAccessGate();

        $this->registerRequestRebindHandler();

    }

    protected function registerAliases()
    {
        $aliases = [
            'ldap' => ['Aimme\Ldap\AuthManager', 'Aimme\Ldap\Contracts\Factory']
        ];

        foreach ($aliases as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->app->alias($key, $alias);
            }
        }
    }

    /**
     * Register the authenticator services.
     *
     * @return void
     */
    protected function registerAuthenticator()
    {
        $this->app->singleton('ldap', function ($app) {
            // Once the authentication service has actually been requested by the developer
            // we will set a variable in the application indicating such. This helps us
            // know that we need to set any queued cookies in the after event later.
            $app['ldap.loaded'] = true;

            return new AuthManager($app);
        });

        $this->app->singleton('ldap.driver', function ($app) {
            return $app['ldap']->guard();
        });
    }

    /**
     * Register a resolver for the authenticated user.
     *
     * @return void
     */
    protected function registerUserResolver()
    {
        $this->app->bind(
            AuthenticatableContract::class, function ($app) {
                return call_user_func($app['ldap']->userResolver());
            }
        );
    }

    /**
     * Register the access gate service.
     *
     * @return void
     */
    protected function registerAccessGate()
    {
        $this->app->singleton(GateContract::class, function ($app) {
            return new Gate($app, function () use ($app) {
                return call_user_func($app['ldap']->userResolver());
            });
        });
    }

    /**
     * Register a resolver for the authenticated user.
     *
     * @return void
     */
    protected function registerRequestRebindHandler()
    {
        $this->app->rebinding('request', function ($app, $request) {
            $request->setUserResolver(function ($guard = null) use ($app) {
                return call_user_func($app['ldap']->userResolver(), $guard);
            });
        });
    }
}