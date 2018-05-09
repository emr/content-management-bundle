<?php

namespace Emr\CMBundle\Middleware;

use Doctrine\ORM\EntityManager;

class LocalizedConstantMiddleware extends BaseMiddleware
{
    use Traits\GeneralConstant;

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em, array $baseArgs)
    {
        $this->em = $em;
        parent::__construct(...$baseArgs);
    }

    private function newLocalizedConstantAction()
    {
        $this->getGeneralConstant();
    }

    public function preNew()
    {
        $this->newLocalizedConstantAction();
    }
}