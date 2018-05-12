<?php

namespace Emr\CMBundle\Middleware;

use Emr\CMBundle\Configuration\EntityConfig;

abstract class BaseMiddleware
{
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
        $entity
    ) {
        $this->settings = $settings;
        $this->cmsEntityConfig = $cmsEntityConfig;
        if (is_array($entity))
            $this->easyAdminEntityConfig = $entity;
        else
            $this->entity = $entity;
    }

    public function preInitialize()
    {
    }

    public function postInitialize()
    {
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