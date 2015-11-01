<?php
namespace Sergiors\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Sergiors\Silex\Doctrine\ManagerRegistry;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class DoctrineManagerRegistryServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['doctrine'] = $app->share(function (Application $app) {
            $container = new \Pimple();
            $ems = $app['ems'];
            $dbs = $app['dbs'];

            $connections = array_map(function ($name) use ($container, $dbs) {
                $container['dbs.'.$name] = $dbs[$name];
                return 'dbs.'.$name;
            }, $dbs->keys());

            $managers = array_map(function ($name) use ($container, $ems) {
                $container['ems.'.$name] = $ems[$name];
                return 'ems.'.$name;
            }, $ems->keys());

            return new ManagerRegistry(
                $container,
                $connections,
                $managers,
                $app['dbs.default'],
                $app['ems.default']
            );
        });

        $app['validator.unique'] = $app->share(function (Application $app) {
            if (!class_exists('Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator')) {
                return;
            }

            return new UniqueEntityValidator($app['doctrine']);
        });

        $app['validator.validator_service_ids'] = [
            'doctrine.orm.validator.unique' => 'validator.unique'
        ];

        if (isset($app['form.extensions'])) {
            $app['form.extensions'] = $app->share(
                $app->extend('form.extensions', function ($extensions) use ($app) {
                    if (class_exists('Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension')) {
                        $extensions[] = new DoctrineOrmExtension($app['doctrine']);
                    }

                    return $extensions;
                })
            );
        }
    }

    public function boot(Application $app)
    {
    }
}
