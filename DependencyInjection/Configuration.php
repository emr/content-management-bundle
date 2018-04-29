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
                ->booleanNode('make_easy_admin_config')
                    ->defaultTrue()
                ->end()
                ->arrayNode('easy_admin_settings')
                    ->children()
                        ->booleanNode('use_security')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
