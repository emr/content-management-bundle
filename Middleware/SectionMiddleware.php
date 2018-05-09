<?php

namespace Emr\CMBundle\Middleware;

use Emr\CMBundle\Entity\Section;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class SectionMiddleware extends BaseMiddleware
{
    /**
     * @var Section
     */
    protected $entity;

    /**
     * @var PropertyAccessor
     */
    protected $accessor;

    public function __construct(PropertyAccessor $accessor, array $baseArgs)
    {
        $this->accessor = $accessor;
        parent::__construct(...$baseArgs);
    }

    public function preUpdate()
    {
        $prop = $this->cmsEntityConfig->getSectionProperties()[get_class($this->entity)];

        foreach ($this->entity->getRemovedPages() as $removedPage)
            $this->accessor->setValue($removedPage, $prop, null);

        foreach ($this->entity->getNewPages() as $newPage)
            $this->accessor->setValue($newPage, $prop, $this->entity);
    }
}