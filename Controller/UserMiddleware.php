<?php

namespace Emr\CMBundle\Controller;

trait UserMiddleware
{
    public function createNewUserEntity()
    {
        return $this->get('fos_user.user_manager')->createUser();
    }

    public function persistUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
        parent::persistEntity($user);
    }

    public function updateUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
        parent::updateEntity($user);
    }

    // security

    public function editUserAction()
    {
        $user = $this->request->attributes->get('easyadmin')['item'];
        $logged = $this->get('security.token_storage')->getToken()->getUser();

        $granted = !array_diff($user->getRoles(), $logged->getRoles());

        if (!$granted)
            throw $this->createAccessDeniedException("[21] Access denied.");

        return parent::editAction();
    }

    public function deleteUserAction()
    {
        $user = $this->request->attributes->get('easyadmin')['item'];
        $logged = $this->get('security.token_storage')->getToken()->getUser();

        $granted = !array_diff($user->getRoles(), $logged->getRoles());

        if (!$granted)
            throw $this->createAccessDeniedException("[22] Access denied.");

        return parent::deleteAction();
    }
}