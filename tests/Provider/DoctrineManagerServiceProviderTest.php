<?php
namespace Inbep\Silex\Provider;

use Silex\Application;
use Silex\WebTestCase;
use Silex\Provider\DoctrineServiceProvider;

class DoctrineManagerServiceProviderTest extends WebTestCase
{
    /**
     * @test
     */
    public function register()
    {
        $app = $this->createApplication();
        $app->register(new DoctrineServiceProvider());
        $app->register(new DoctrineOrmServiceProvider());
        $app->register(new DoctrineManagerRegistryServiceProvider());
    }

    public function createApplication()
    {
        $app = new Application();
        $app['debug'] = true;
        $app['exception_handler']->disable();
        return $app;
    }
}
