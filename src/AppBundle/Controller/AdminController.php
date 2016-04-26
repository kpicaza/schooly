<?php

namespace AppBundle\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use AppBundle\Model\User\UserInterface as User;

class AdminController extends EasyAdminController
{
    public function createNewUserEntity()
    {
        return $this->get('fos_user.user_manager')->createUser();
    }

    public function prePersistUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
    }

    public function preUpdateUserEntity($entity)
    {
        if ($entity instanceof User) {
            $this->container->get('fos_user.user_manager')->updateUser($entity, false);
        }
    }
}
