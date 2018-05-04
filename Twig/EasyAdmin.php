<?php

namespace Emr\CMBundle\Twig;

use Emr\CMBundle\EasyAdmin\EasyAdminEntityNaming;

class EasyAdmin extends \Twig_Extension
{
    /**
     * @var EasyAdminEntityNaming $naming
     */
    private $naming;

    /**
     * @param EasyAdminEntityNaming $naming
     */
    public function __construct(EasyAdminEntityNaming $naming)
    {
        $this->naming = $naming;
    }

    public function getFunctions()
    {
        return [new \Twig_SimpleFunction('easyadmin_entity_name', [$this, 'name'])];
    }

    public function name($class)
    {
        return $this->naming->name($class);
    }
}