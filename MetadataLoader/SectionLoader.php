<?php

namespace Emr\CMBundle\MetadataLoader;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Emr\CMBundle\Configuration\EntityConfig;

class SectionLoader extends AbstractLoader
{
    public function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->createOneToMany('page', $this->config->getClass(EntityConfig::PAGE))
            ->mappedBy($this->config->getSection($metadata->getName())['property'])
        ->build();
    }
}