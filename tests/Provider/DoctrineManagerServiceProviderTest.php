<?php

namespace Sergiors\Silex\Tests\Provider;

use Pimple\Container;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Sergiors\Silex\Provider\DoctrineCacheServiceProvider;
use Sergiors\Silex\Provider\DoctrineOrmServiceProvider;
use Sergiors\Silex\Provider\DoctrineManagerRegistryServiceProvider;

class DoctrineManagerServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function register()
    {
        $app = new Container();

        $app->register(new ValidatorServiceProvider());
        $app->register(new DoctrineServiceProvider());
        $app->register(new DoctrineCacheServiceProvider());
        $app->register(new DoctrineOrmServiceProvider());
        $app->register(new DoctrineManagerRegistryServiceProvider());

        $this->assertArrayHasKey('doctrine', $app);
    }
}
