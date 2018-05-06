<?php

namespace Emr\CMBundle\Middleware;

use Emr\CMBundle\Entity\Section;

class SectionMiddleware extends BaseMiddleware
{
    /**
     * @var Section
     */
    protected $entity;

    public function preUpdate()
    {
        $prop = $this->cmsEntityConfig->getSectionProperties()[get_class($this->entity)];

        foreach ($this->entity->getRemovedPages() as $removedPage)
            $removedPage->{$prop} = null;

        foreach ($this->entity->getNewPages() as $newPage)
            $newPage->{$prop} = $this->entity;
    }
}