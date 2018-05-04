<?php

namespace Emr\CMBundle\EasyAdmin;

use Emr\CMBundle\Exception\SameEntityNameException;

class EasyAdminEntityNaming
{
    /**
     * @var array
     */
    private $names = [
        'constant' => 'GeneralConstant',
        'localized_constant' => 'LocalizedConstant',
        'page_admin' => 'PageAdmin',
        'page_layout' => 'PageLayout',
    ];

    /**
     * @var array
     */
    private $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function name(string $nameOrClass, string $class = null, $exception = false): string
    {
        if ($class)
        {
            if (in_array($nameOrClass, $this->names) && $exception)
                throw new SameEntityNameException("Entity name \"{$nameOrClass}\" is already using. Define another name for it.");

            $this->names[$class] = $nameOrClass;
            $nameOrClass = $class;
        }
        else
            $this->names[$nameOrClass] =  $this->names[$nameOrClass] ?? basename(str_replace('\\', '/', $nameOrClass));

        return $this->settings['entity_prefix'].$this->names[$nameOrClass];
    }
}