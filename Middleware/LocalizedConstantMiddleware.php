<?php

namespace Emr\CMBundle\Middleware;

use Doctrine\ORM\EntityManager;
use Emr\CMBundle\Configuration\EntityConfig;
use Symfony\Component\HttpFoundation\Request;

class LocalizedConstantMiddleware extends BaseMiddleware
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

    private function newLocalizedConstantAction()
    {
        $this->getGeneralConstant();
    }

    /**
     * Eğer 1 tane yerel ayar varsa, listelemeyi geçip düzenleme ekranını getir.
     */
    private function checkForSingleLocale()
    {
        if (
            'list' === $this->request->query->get('action', 'list') &&
            1 === count($constants = $this->em->getRepository($this->cmsEntityConfig->getClass(EntityConfig::LOCALIZED_CONSTANT))->findAll())
        ) {
            $this->request->query->set('action', 'edit');
            $this->request->query->set('id', $constants[0]->getLocale());
            $this->request->attributes->set('easyadmin', array_merge(
                $this->request->attributes->get('easyadmin'),
                ['item' => $constants[0]]
            ));
        }
    }

    public function postInitialize()
    {
        $this->checkForSingleLocale();
    }

    public function preNew()
    {
        $this->newLocalizedConstantAction();
    }
}