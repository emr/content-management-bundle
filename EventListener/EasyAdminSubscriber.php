<?php

namespace Emr\CMBundle\EventListener;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Emr\CMBundle\Configuration\EntityConfig;
use Emr\CMBundle\EasyAdmin\EasyAdminEntityNaming;
use Emr\CMBundle\Middleware;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * @var EntityConfig
     */
    private $cmsEntityConfig;

    /**
     * @var EasyAdminEntityNaming
     */
    private $naming;

    /**
     * EasyAdmin settings
     * @var array
     */
    private $settings;

    public function __construct(
        PropertyAccessor $accessor,
        EntityConfig $cmsEntityConfig,
        EasyAdminEntityNaming $naming,
        array $settings
    ) {
        $this->accessor = $accessor;
        $this->cmsEntityConfig = $cmsEntityConfig;
        $this->naming = $naming;
        $this->settings = $settings;
    }

    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::PRE_LIST   => ['preList'],
            EasyAdminEvents::PRE_EDIT   => ['preEdit'],
            EasyAdminEvents::PRE_NEW    => ['preNew'],
            EasyAdminEvents::POST_NEW   => ['postNew'],
            EasyAdminEvents::PRE_UPDATE => ['preUpdate'],
        ];
    }

    public function preList(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if ($entity['name'] == $this->naming->get(EasyAdminEntityNaming::CONSTANT))
        {
            $this->createConstantMiddleware($event)->preList();
        }
    }

    public function preEdit(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if ($entity['name'] == $this->naming->get(EasyAdminEntityNaming::CONSTANT))
        {
            $this->createConstantMiddleware($event)->preEdit();
        }
    }

    public function preNew(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if ($entity['name'] == $this->naming->get(EasyAdminEntityNaming::LOCALIZED_CONSTANT))
        {
            $this->createLocalizedConstantMiddleware($event)->preNew();
        }
    }

    public function postNew(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if ($this->cmsEntityConfig->isSection($entity))
        {
            $this->createSectionMiddleware($event)->preUpdate();
        }
    }

    public function preUpdate(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if ($this->cmsEntityConfig->isSection($entity))
        {
            $this->createSectionMiddleware($event)->preUpdate();
        }
    }

    // factories

    private function createConstantMiddleware(GenericEvent $event)
    {
        return new Middleware\ConstantMiddleware(
            $event->getArgument('em'),
            $event->getArgument('request'),
            [
                $this->settings,
                $this->cmsEntityConfig,
                $event->getArgument('entity')
            ]
        );
    }

    private function createLocalizedConstantMiddleware(GenericEvent $event)
    {
        return new Middleware\LocalizedConstantMiddleware(
            $event->getArgument('em'),
            [
                $this->settings,
                $this->cmsEntityConfig,
                $event->getArgument('entity')
            ]
        );
    }

    private function createSectionMiddleware(GenericEvent $event)
    {
        return new Middleware\SectionMiddleware(
            $this->accessor,
            [
                $this->settings,
                $this->cmsEntityConfig,
                $event->getArgument('entity')
            ]
        );
    }
}