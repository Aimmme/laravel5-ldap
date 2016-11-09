<?php

namespace Aimme\Ldap;

use Illuminate\Console\Command;
use Illuminate\Console\AppNamespaceDetectorTrait;

class MakeLdapAuthCommand extends Command
{
    use AppNamespaceDetectorTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:ldap-auth {--views : Only scaffold the authentication views}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creates ldap authentication for the project';

        /**
     * The views that need to be exported.
     *
     * @var array
     */
    protected $views = [
        'auth/ldap/login.stub' => 'auth/ldap/login.blade.php',
        'auth/ldap/app.stub' => 'auth/ldap/app.blade.php',
        'auth/ldap/home.stub' => 'auth/ldap/home.blade.php',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->createDirectories();

        $this->exportViews();

        if (! $this->option('views')) {
            file_put_contents(
                app_path('Http/Controllers/Auth/LdapController.php'),
                $this->compileControllerStub(__DIR__.'/stubs/make/controllers/LdapController.stub')
            );

            $this->info('Auth/LdapController added');

            file_put_contents(
                app_path('Http/Controllers/Auth/HomeController.php'),
                $this->compileControllerStub(__DIR__.'/stubs/make/controllers/HomeController.stub')
            );

            $this->info('Auth/HomeController added');

            file_put_contents(
                base_path('routes/web.php'),
                file_get_contents(__DIR__.'/stubs/make/routes.stub'),
                FILE_APPEND
            );

            $this->info('routes added');
        }

        $this->info('Ldap Authentication setup finished successfully. remember to publish vendor files.');
    }

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories()
    {
        if (! is_dir(base_path('app/Http/Controllers/Auth'))) {
            mkdir(base_path('app/Http/Controllers/Auth'), 0755, true);
        }

        if (! is_dir(base_path('resources/views/auth/ldap'))) {
            mkdir(base_path('resources/views/auth/ldap'), 0755, true);
        }
    }

    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportViews()
    {
        foreach ($this->views as $key => $value) {
            copy(
                __DIR__.'/stubs/make/views/'.$key,
                base_path('resources/views/'.$value)
            );
        }
        $this->info('views created.');
    }

    /**
     * Compiles the Controller stub.
     *
     * @return string
     */
    protected function compileControllerStub($stub)
    {
        return str_replace(
            '{{namespace}}',
            $this->getAppNamespace(),
            file_get_contents($stub)
        );
    }
}
