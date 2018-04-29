<?php

namespace Emr\CMBundle\MetadataLoader;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

class LocalizedConstantLoader extends AbstractLoader
{
    public function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->createManyToOne('constant', $this->config->getGeneralConstantClass())
            ->fetchEager()
            ->addJoinColumn('constant_id', 'id')
        ->build();
    }
}