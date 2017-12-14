<?php

namespace Sergiors\Pimple\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Sergiors\Pimple\Doctrine\ManagerRegistry;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class DoctrineManagerRegistryServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        if (!isset($container['validator'])) {
            throw new \LogicException(
                'You must register the ValidatorServiceProvider to use the DoctrineManagerRegistryServiceProvider.'
            );
        }

        $container['doctrine'] = function () use ($container) {
            $container = new Container();
            $ems = $container['ems'];
            $dbs = $container['dbs'];

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
                $container['dbs.default'],
                $container['ems.default']
            );
        };

        $container['validator.unique'] = function () use ($container) {
            if (!class_exists('Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator')) {
                return;
            }

            return new UniqueEntityValidator($container['doctrine']);
        };

        $container['validator.validator_service_ids'] = array_merge($container['validator.validator_service_ids'], [
            'doctrine.orm.validator.unique' => 'validator.unique',
        ]);

        if (isset($container['form.extensions'])) {
            $container['form.extensions'] = $container->extend('form.extensions', function ($extensions) use ($container) {
                if (class_exists('Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension')) {
                    $extensions[] = new DoctrineOrmExtension($container['doctrine']);
                }

                return $extensions;
            });
        }
    }
}
