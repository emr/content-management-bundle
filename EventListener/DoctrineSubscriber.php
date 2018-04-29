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

        if ($this->config->isGeneralConstantClass($metadata->getName()))
            $loader = MetadataLoader\GeneralConstantLoader::class;

        if ($this->config->isLocalizedConstantClass($metadata->getName()))
            $loader = MetadataLoader\LocalizedConstantLoader::class;

        if ($this->config->isPageClass($metadata->getName()))
            $loader = MetadataLoader\PageLoader::class;

        if ($this->config->isSection($metadata->getName()))
            $loader = MetadataLoader\SectionLoader::class;

        if (isset($loader))
            (new $loader($this->config))->loadMetadata($metadata);
    }
}