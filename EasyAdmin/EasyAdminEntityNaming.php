<?php

namespace Emr\CMBundle\EasyAdmin;

use Emr\CMBundle\Exception\EntityNameNotFoundException;
use Emr\CMBundle\Exception\SameEntityNameException;

class EasyAdminEntityNaming
{
    // global classes
    public const CONSTANT = 'constant';
    public const LOCALIZED_CONSTANT = 'localized_constant';
    public const PAGE_ADMIN = 'page_admin';
    public const PAGE_LAYOUT = 'page_layout';

    /**
     * @var array
     */
    private $names = [];

    /**
     * @var array
     */
    private $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;

        // register global used names
        foreach ([
            self::CONSTANT => 'GeneralConstant',
            self::LOCALIZED_CONSTANT => 'LocalizedConstant',
            self::PAGE_ADMIN => 'PageAdmin',
            self::PAGE_LAYOUT => 'PageLayout',
        ] as $class => $name)
            $this->set($name, $class);
    }

    /**
     * @param string $name       | name or class
     * @param string|null $class | class
     * @param bool $exception
     * @return string|null
     */
    public function set(string $name, string $class = null, bool $exception = true): ?string
    {
        if (!$class)
        {
            $class = $name;
            $name = basename(str_replace('\\', '/', $class));
        }

        if (in_array($name, $this->names))
        {
            if ($exception)
                throw new SameEntityNameException("Entity name \"{$name}\" is already using. Define another name for it.");
        }
        else
        {
            $this->names[$class] = $name;
        }

        return $this->get($class);
    }

    /**
     * @param string $class
     * @param bool $exception
     * @return string|null
     */
    public function get(string $class, bool $exception = false): ?string
    {
        if (!isset($this->names[$class]))
        {
            if ($exception)
                throw new EntityNameNotFoundException("Entity class \"{$class}\" not registered.");
            return null;
        }

        return $this->settings['entity_prefix'].$this->names[$class];
    }
}