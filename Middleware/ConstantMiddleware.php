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