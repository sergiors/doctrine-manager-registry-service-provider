<?php

namespace Sergiors\Pimple\Tests\Provider;

use Pimple\Container;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Sergiors\Pimple\Provider\DoctrineCacheServiceProvider;
use Sergiors\Pimple\Provider\DoctrineOrmServiceProvider;
use Sergiors\Pimple\Provider\DoctrineManagerRegistryServiceProvider;

class DoctrineManagerServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function register()
    {
        $container = new Container();

        $container->register(new ValidatorServiceProvider());
        $container->register(new DoctrineServiceProvider());
        $container->register(new DoctrineCacheServiceProvider());
        $container->register(new DoctrineOrmServiceProvider());
        $container->register(new DoctrineManagerRegistryServiceProvider());

        $this->assertArrayHasKey('doctrine', $container);
    }
}
