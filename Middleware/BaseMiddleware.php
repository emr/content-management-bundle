<?php

namespace Emr\CMBundle\Middleware;

use Doctrine\ORM\EntityManager;
use Emr\CMBundle\Configuration\EntityConfig;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseMiddleware
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var EntityConfig
     */
    protected $cmsEntityConfig;

    /**
     * EasyAdmin settings
     * @var array
     */
    protected $settings;

    /**
     * @var array
     */
    protected $easyAdminEntityConfig;

    /**
     * @var object
     */
    protected $entity;

    public function __construct(
        array $settings,
        EntityConfig $cmsEntityConfig,
        Request $request,
        EntityManager $em,
        $entity
    ) {
        $this->settings = $settings;
        $this->cmsEntityConfig = $cmsEntityConfig;
        $this->request = $request;
        $this->em = $em;
        if (is_array($entity))
            $this->easyAdminEntityConfig = $entity;
        else
            $this->entity = $entity;
    }

    public function preList()
    {
    }

    public function preEdit()
    {
    }

    public function preNew()
    {
    }

    public function preUpdate()
    {
    }
}