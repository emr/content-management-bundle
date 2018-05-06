<?php

namespace Emr\CMBundle\EventListener;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Emr\CMBundle\Configuration\EntityConfig;
use Emr\CMBundle\EasyAdmin\EasyAdminEntityNaming;
use Emr\CMBundle\Middleware;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{
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

    public function __construct(EntityConfig $cmsEntityConfig, EasyAdminEntityNaming $naming, array $settings)
    {
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
            EasyAdminEvents::PRE_UPDATE => ['preUpdate'],
        ];
    }

    public function preList(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if ($entity['name'] == $this->naming->get(EasyAdminEntityNaming::CONSTANT))
        {
            $middleware = new Middleware\ConstantMiddleware(
                $this->settings,
                $this->cmsEntityConfig,
                $event->getArgument('request'),
                $event->getArgument('em'),
                $event->getArgument('entity')
            );
            $middleware->preList();
        }
    }

    public function preEdit(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if ($entity['name'] == $this->naming->get(EasyAdminEntityNaming::LOCALIZED_CONSTANT))
        {
            $middleware = new Middleware\LocalizedConstantMiddleware(
                $this->settings,
                $this->cmsEntityConfig,
                $event->getArgument('request'),
                $event->getArgument('em'),
                $event->getArgument('entity')
            );
            $middleware->preEdit();
        }
    }

    public function preNew(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if ($entity['name'] == $this->naming->get(EasyAdminEntityNaming::LOCALIZED_CONSTANT))
        {
            $middleware = new Middleware\LocalizedConstantMiddleware(
                $this->settings,
                $this->cmsEntityConfig,
                $event->getArgument('request'),
                $event->getArgument('em'),
                $event->getArgument('entity')
            );
            $middleware->preNew();
        }
    }

    public function preUpdate(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if ($this->cmsEntityConfig->isSection($entity))
        {
            $middleware = new Middleware\SectionMiddleware(
                $this->settings,
                $this->cmsEntityConfig,
                $event->getArgument('request'),
                $event->getArgument('em'),
                $event->getArgument('entity')
            );
            $middleware->preUpdate();
        }
    }
}