<?php

namespace Emr\CMBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

trait SecurityMiddleware
{
    private function checkPermissions($config, $deny = false, $key = 'role_require')
    {
        if (!$this->cmsConfig['easy_admin_settings']['use_security']) return true;

        $permission = false;

        if (isset($config[$key]) && !empty($config[$key]))
            $permission = $config[$key];

        if ($permission)
            if ($deny)
                $this->denyAccessUnlessGranted($permission, null, "[1] Access denied.");
            else
                return $this->isGranted($permission);
        else
            return true;
    }

    private function checkEntityActionPermissions()
    {
        if (!$this->cmsConfig['easy_admin_settings']['use_security']) return;

        $config = $this->request->attributes->get('easyadmin');

        if (
            $roles = $config['entity']['action_roles'] ?? $config['entity'][$config['view']]['action_roles'] ?? null
        )
            foreach ($roles as $action => $role)
                if (!$this->isGranted($role))
                    $this->entity['disabled_actions'][] = $action;
    }

    public function createEntityFormBuilder($entity, $view)
    {
        $builder = parent::createEntityFormBuilder($entity, $view);

        if (!$this->cmsConfig['easy_admin_settings']['use_security']) return $builder;

        foreach ($builder->all() as $name => $field)
            if (!$this->checkPermissions($this->entity[$view]['fields'][$name]))
                $builder->remove($name);

        return $builder;
    }
}