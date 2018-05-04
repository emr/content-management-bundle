<?php

namespace Emr\CMBundle\Controller;

trait PageMiddleware
{
    /**
     * * filter results by page key
     */
    protected function listPageAdminAction()
    {
        if ($key = $this->request->query->get('_key'))
            $this->entity['list']['dql_filter'] = "entity.key = '{$key}'";

        return parent::listAction();
    }
}