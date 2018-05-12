<?php

namespace Emr\CMBundle\Middleware;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class ConstantMiddleware extends BaseMiddleware
{
    use Traits\GeneralConstant;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Request
     */
    protected $request;

    public function __construct(EntityManager $em, Request $request, array $baseArgs)
    {
        $this->em = $em;
        $this->request = $request;
        parent::__construct(...$baseArgs);
    }

    private function editGeneralConstantAction()
    {
        $this->request->query->set('id', 1);
        $this->request->attributes->set('easyadmin', array_merge(
            $this->request->attributes->get('easyadmin'),
            ['item' => $this->getGeneralConstant()]
        ));
    }

    public function preList()
    {
        $this->editGeneralConstantAction();
    }

    public function preEdit()
    {
        $this->editGeneralConstantAction();
    }
}