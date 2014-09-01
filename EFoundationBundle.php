<?php

namespace LMammino\EFoundationBundle;

use LMammino\EFoundation\Common\Doctrine\MappingLocator;
use LMammino\EFoundationBundle\DependencyInjection\CompilerPass\ResolveDoctrineTargetEntitiesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class EFoundationBundle
 *
 * @package LMammino\EFoundationBundle
 */
class EFoundationBundle extends Bundle
{
    const DOCTRINE_ORM_MAPPINGS_PASS_CLASS =
        'Doctrine\\Bundle\\DoctrineBundle\\DependencyInjection\\Compiler\\DoctrineOrmMappingsPass';

    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        // Loads default resolves from library
        $resolves = MappingLocator::getDefaultResolves();
        if (!empty($resolves)) {
            $container->addCompilerPass(new ResolveDoctrineTargetEntitiesPass($resolves));
        }

        // Loads mappings from library
        $mappings = MappingLocator::getMappings();
        if (!empty($mappings)) {
            if(class_exists(self::DOCTRINE_ORM_MAPPINGS_PASS_CLASS)) {
                $mappingsPassClassName = self::DOCTRINE_ORM_MAPPINGS_PASS_CLASS;
                foreach ($mappings as $path => $namespace) {
                    $container->addCompilerPass($mappingsPassClassName::createXmlMappingDriver(
                        array($path => $namespace),
                        array('doctrine.orm.entity_manager')
                    ));
                }
            }
        }
    }
}
