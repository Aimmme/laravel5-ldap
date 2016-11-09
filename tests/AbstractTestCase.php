<?php
namespace Aimme\Ldap\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class AbstractTestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__.'/migrations'),
        ]);

        $this->withFactories(__DIR__.'/factories');
    }
    /**
     * Get base path.
     *
     * @return string
     */
    protected function getBasePath()
    {
        return __DIR__.'/../vendor/orchestra/testbench/fixture';

    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('auth.providers.users.driver', 'eloquent');
        $app['config']->set('auth.providers.users.model', 'Aimme\Ldap\Tests\User');

        $app['config']->set('ldap.providers.users.driver', 'ldap');
        $app['config']->set('ldap.providers.users.model', 'Aimme\Ldap\Tests\User');
        $app['config']->set('ldap.guards.web.driver', 'session');
        $app['config']->set('ldap.guards.web.provider', 'users');
        $app['config']->set('ldap.defaults.guard','web');
    }

    protected function getPackageProviders($app)
    {
        return [
            \Aimme\Ldap\LdapServiceProvider::class
        ];
    }


    protected function getPackageAliases($app)
    {
        return [
            'Ldap' => \Aimme\Ldap\Facades\Ldap::class
        ];
    }
}