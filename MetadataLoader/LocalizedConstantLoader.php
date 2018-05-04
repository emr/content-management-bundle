<?php

namespace Emr\CMBundle\MetadataLoader;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Emr\CMBundle\Configuration\EntityConfig;

class LocalizedConstantLoader extends AbstractLoader
{
    public function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->createManyToOne('constant', $this->config->getClass(EntityConfig::CONSTANT))
            ->fetchEager()
            ->addJoinColumn('constant_id', 'id', false)
        ->build();
    }
}