<?php

namespace Emr\CMBundle\MetadataLoader;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

class PageLoader extends AbstractLoader
{
    public function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->createManyToOne('constant', $this->config->getLocalizedConstantClass())
            ->addJoinColumn('locale', 'locale')
        ->build();

        foreach ($this->config->getSections() as $section)
        {
            $builder->createManyToOne($section['property'], $section['class'])
                ->addJoinColumn("{$section['property']}_section_id", 'id')
            ->build();
        }
    }
}