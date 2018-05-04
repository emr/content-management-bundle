<?php

namespace Emr\CMBundle\MetadataLoader;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Emr\CMBundle\Configuration\EntityConfig;

class PageLoader extends AbstractLoader
{
    public function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->createManyToOne('constant', $this->config->getClass(EntityConfig::LOCALIZED_CONSTANT))
            ->addJoinColumn('locale', 'locale')
        ->build();

        foreach ($this->config->getSections() as $section)
        {
            $builder->createManyToOne($section['property'], $section['class'])
                ->inversedBy('page')
                ->addJoinColumn("{$section['property']}_section_id", 'id')
                ->cascadePersist()
            ->build();
        }
    }
}