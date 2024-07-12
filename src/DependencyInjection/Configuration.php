<?php

namespace Flucava\RequestContextBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Philipp Marien
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('request_context');
        $root = $builder->getRootNode()->children();

        $root->scalarNode('storage_path')
            ->defaultValue('%kernel.project_dir%/var/request-context')
            ->cannotBeEmpty();

        $root->arrayNode('main_uris')
            ->defaultValue([])
            ->scalarPrototype();

        $root->variableNode('default_settings')
            ->defaultValue([]);

        $builder->getRootNode()->addDefaultsIfNotSet();

        return $builder;
    }
}
