<?php

namespace Emr\CMBundle\Service;

use Doctrine\ORM\EntityManager;

class CMS
{
    public function __construct(EntityManager $em)
    {
        dump($em);
    }
}