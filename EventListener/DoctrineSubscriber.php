<?php

namespace Emr\CMBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Emr\CMBundle\Configuration\EntityConfig;
use Emr\CMBundle\MetadataLoader;

class DoctrineSubscriber implements EventSubscriber
{
    /**
     * @var EntityConfig
     */
    private $config;

    public function __construct(EntityConfig $config)
    {
        $this->config = $config;
    }

    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $e)
    {
        $metadata = $e->getClassMetadata();

        switch ($metadata->getName())
        {
            case $this->config->getClass(EntityConfig::CONSTANT):
                $loader = MetadataLoader\GeneralConstantLoader::class;
                break;
            case $this->config->getClass(EntityConfig::LOCALIZED_CONSTANT):
                $loader = MetadataLoader\LocalizedConstantLoader::class;
                break;
            case $this->config->getClass(EntityConfig::PAGE):
                $loader = MetadataLoader\PageLoader::class;
                break;
            default:
                if ($this->config->isSection($metadata->getName()))
                    $loader = MetadataLoader\SectionLoader::class;
        }

        if (isset($loader))
            (new $loader($this->config))->loadMetadata($metadata);
    }
}