<?php

namespace Emr\CMBundle\Twig;

use Emr\CMBundle\EasyAdmin\EasyAdminEntityNaming;
use Emr\CMBundle\Exception\EntityNameNotFoundException;

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
        return [new \Twig_SimpleFunction('easyadmin_entity_name', [$this, 'getName'])];
    }

    public function getName($class)
    {
        try {
            return $this->naming->get($class, true);
        } catch (EntityNameNotFoundException $e) {
            return $this->naming->set($class);
        }
    }
}