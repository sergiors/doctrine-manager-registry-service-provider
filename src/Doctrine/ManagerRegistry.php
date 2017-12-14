<?php

namespace Sergiors\Pimple\Doctrine;

use Pimple\Container;
use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Doctrine\ORM\ORMException;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class ManagerRegistry extends AbstractManagerRegistry
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $container
     * @param array     $connections
     * @param array     $entityManagers
     * @param string    $defaultConnection
     * @param string    $defaultEntityManager
     */
    public function __construct(
        Container $container,
        array $connections,
        array $entityManagers,
        $defaultConnection,
        $defaultEntityManager
    ) {
        $this->setContainer($container);
        parent::__construct(
            'ORM',
            $connections,
            $entityManagers,
            $defaultConnection,
            $defaultEntityManager,
            'Doctrine\ORM\Proxy\Proxy'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getService($name)
    {
        return $this->container[$name];
    }

    /**
     * {@inheritdoc}
     */
    protected function resetService($name)
    {
        $this->container[$name] = null;
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(Container $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getAliasNamespace($alias)
    {
        foreach (array_keys($this->getManagers()) as $name) {
            try {
                return $this->getManager($name)->getConfiguration()->getEntityNamespace($alias);
            } catch (ORMException $e) {
            }
        }
        throw ORMException::unknownEntityNamespace($alias);
    }
}
