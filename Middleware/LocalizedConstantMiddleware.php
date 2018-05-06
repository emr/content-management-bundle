<?php

namespace Emr\CMBundle\Middleware;

use Emr\CMBundle\EasyAdmin\EasyAdminEntityNaming;

class LocalizedConstantMiddleware extends BaseMiddleware
{
    use Traits\GeneralConstant;

    private function newLocalizedConstantAction()
    {
        $this->getGeneralConstant();
    }

    public function preNew()
    {
        $this->newLocalizedConstantAction();
    }
}