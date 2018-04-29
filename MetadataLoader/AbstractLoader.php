<?php

namespace Emr\CMBundle\MetadataLoader;

use Doctrine\ORM\Mapping\ClassMetadata;
use Emr\CMBundle\Configuration\EntityConfig;

abstract class AbstractLoader
{
    /**
     * @var EntityConfig
     */
    protected $config;

    public function __construct(EntityConfig $config)
    {
        $this->config = $config;
    }

    abstract public function loadMetadata(ClassMetadata $metadata);
}