<?php

namespace Emr\CMBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EntityConfigPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        dump(1);
    }
}