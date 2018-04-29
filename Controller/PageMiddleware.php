<?php

namespace Emr\CMBundle\Controller;

use Emr\CMBundle\Entity\Page;

trait PageMiddleware
{
    /**
     * * filter results by page key
     */
    protected function listAction()
    {
        if ($this->entity['class'] == Page::class)
        {
            // filter results
            if ($page = $this->request->query->get('_page'))
                $this->entity['list']['dql_filter'] = "entity.page = '{$page}'";
        }

        return parent::listAction();
    }
}