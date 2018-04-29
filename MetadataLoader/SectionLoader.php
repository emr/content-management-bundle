<?php

namespace Emr\CMBundle\MetadataLoader;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

class SectionLoader extends AbstractLoader
{
    public function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

//        $builder->createOneToMany($this->config->getPageClass());
    }
}