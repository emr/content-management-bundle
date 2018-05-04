<?php

namespace Emr\CMBundle\Controller;

use Emr\CMBundle\Configuration\EntityConfig;

trait ConstantMiddleware
{
    protected function getGeneralConstant()
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

    protected function editGeneralConstantAction()
    {
        $this->request->query->set('id', 1);
        $this->request->attributes->set('easyadmin', array_merge(
            $this->request->attributes->get('easyadmin'),
            ['item' => $this->getGeneralConstant()]
        ));

        return parent::editAction();
    }

    protected function newLocalizedConstantAction()
    {
        $this->getGeneralConstant();

        return parent::newAction();
    }

    protected function listLocalizedConstantAction()
    {
        if (1 === count($constants = $this->em->getRepository($this->cmsEntityConfig->getClass(EntityConfig::LOCALIZED_CONSTANT))->findAll()))
        {
            $this->request->query->set('id', $constants[0]->getLocale());
            $this->request->attributes->set('easyadmin', array_merge(
                $this->request->attributes->get('easyadmin'),
                ['item' => $constants[0]]
            ));
            return parent::editAction();
        }
        return parent::listAction();
    }
}