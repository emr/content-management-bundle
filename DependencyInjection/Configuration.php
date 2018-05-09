<?php

namespace Emr\CMBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('emr_cm')
            ->children()
                ->enumNode('type')
                    ->values(['annotation', /*'php', 'yml'*/])
                    ->defaultValue('annotation')
                ->end()
                ->scalarNode('entity_path')
                    ->defaultValue('%kernel.root_dir%/../src/AppBundle/Entity')
                ->end()
                ->scalarNode('entity_namespace')
                    ->defaultValue('AppBundle\Entity')
                ->end()
                ->booleanNode('make_easy_admin_config')
                    ->defaultTrue()
                ->end()
                ->booleanNode('use_fos_user')
                    ->defaultTrue()
                ->end()
                ->arrayNode('easy_admin_settings')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('auto_config_sections')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('entity_prefix')
                            ->defaultValue('CMS')
                        ->end()
                        ->booleanNode('use_security')
                            ->defaultTrue()
                        ->end()
                        /**
                         * [Class => EntityName]
                         */
                        ->arrayNode('entities')
                            ->addDefaultsIfNotSet()
                        ->end()
                        // todo
                        ->arrayNode('dashboard')
                            ->children()
                                ->arrayNode('entities')
                                    ->arrayPrototype()
                                        ->children()
                                            ->scalarNode('class')->isRequired()->end()
                                            ->scalarNode('identifier')->end()
                                            ->scalarNode('title')->end()
                                            ->integerNode('limit')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
