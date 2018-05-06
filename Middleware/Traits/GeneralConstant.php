<?php

namespace Emr\CMBundle\Middleware\Traits;

use Emr\CMBundle\Configuration\EntityConfig;

trait GeneralConstant
{
    private function getGeneralConstant()
    {
        if (!($item = $this->em->getRepository($this->cmsEntityConfig->getClass(EntityConfig::CONSTANT))->find(1)))
        {
            $class = $this->cmsEntityConfig->getClass(EntityConfig::CONSTANT);
            $item = new $class;
            $item->setId(1);
            $this->em->persist($item);
            $this->em->flush();
        }

        return $item;
    }
}