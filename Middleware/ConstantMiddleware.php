<?php

namespace Emr\CMBundle\Middleware;

use Emr\CMBundle\EasyAdmin\EasyAdminEntityNaming;

class ConstantMiddleware extends BaseMiddleware
{
    use Traits\GeneralConstant;

    private function editGeneralConstantAction()
    {
        $this->request->query->set('id', 1);
        $this->request->attributes->set('easyadmin', array_merge(
            $this->request->attributes->get('easyadmin'),
            ['item' => $this->getGeneralConstant()]
        ));
    }

    public function preEdit()
    {
        $this->editGeneralConstantAction();
    }

//    /**
//     * Eğer 1 tane yerel ayar varsa, listelemeyi geçip düzenleme ekranını getir.
//     */
//    public function listLocalizedConstantAction()
//    {
//        if (1 === count($constants = $this->em->getRepository($this->cmsEntityConfig->getClass(EntityConfig::LOCALIZED_CONSTANT))->findAll()))
//        {
//            $this->request->query->set('id', $constants[0]->getLocale());
//            $this->request->attributes->set('easyadmin', array_merge(
//                $this->request->attributes->get('easyadmin'),
//                ['item' => $constants[0]]
//            ));
//            return parent::editAction();
//        }
//        return parent::listAction();
//    }
}