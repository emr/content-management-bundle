<?php

namespace Emr\CMBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

trait SecurityMiddleware
{
    private function checkPermissions($config, $deny = false, $key = 'role_require')
    {
        $permission = false;

        if (isset($config[$key]))
            $permission = $config[$key];

        if ($permission)
            if ($deny)
                $this->denyAccessUnlessGranted($permission, null, "#1 Access denied. (Erişim engellendi.)");
            else
                return $this->isGranted($permission);
        else
            return true;
    }

    private function checkEntityActionPermissions()
    {
        $config = $this->request->attributes->get('easyadmin');

        if (
        $roles = $config['entity']['action_roles'] ?? $config['entity'][$config['view']]['action_roles'] ?? null
        )
            foreach ($roles as $action => $role)
                if (!$this->isGranted($role))
                    $this->entity['disabled_actions'][] = $action;
    }

    public function initialize(Request $request)
    {
        parent::initialize($request);

        $this->checkPermissions($this->entity, true);
        if ($request->query->get('entity'))
            $this->checkEntityActionPermissions();
    }

    /**
     * @Route("/", name="easyadmin")
     */
    public function indexAction(Request $request)
    {
        try {
            return parent::indexAction($request);
        } catch (ForbiddenActionException $e) {
            return $this->redirectToRoute('fos_user_security_login');
        }
    }

    public function createEntityFormBuilder($entity, $view)
    {
        $builder = parent::createEntityFormBuilder($entity, $view);

        foreach ($builder->all() as $name => $field)
            if (!$this->checkPermissions($this->entity[$view]['fields'][$name]))
                $builder->remove($name);

        return $builder;
    }
}