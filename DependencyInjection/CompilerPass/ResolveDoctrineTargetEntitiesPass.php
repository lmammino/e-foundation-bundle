<?php

namespace LMammino\EFoundationBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ResolveDoctrineTargetEntitiesPass
 *
 * @package LMammino\EFoundationBundle\DependencyInjection\CompilerPass
 */
class ResolveDoctrineTargetEntitiesPass implements CompilerPassInterface
{
    /**
     * @var array $resolves
     */
    protected $resolves;

    /**
     * Constructor
     *
     * @param array $resolves
     */
    public function __construct(array $resolves)
    {
        $this->resolves = $resolves;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $listenerServiceName = 'e_foundation.doctrine.orm.tools.resolve_target_entity_listener';

        if (!$container->hasDefinition($listenerServiceName)) {
            throw new \RuntimeException('Cannot find e-foundation Resolve Target Entity Listener '.
                sprintf('(service "%s")', $listenerServiceName));
        }

        $resolveTargetEntityListener = $container->findDefinition($listenerServiceName);

        foreach ($this->resolves as $interface => $model) {
            $resolveTargetEntityListener
                ->addMethodCall('addResolveTargetEntity', array(
                    $interface,
                    $model,
                    array()
                ))
            ;
        }
    }
}
