<?php

namespace AppBundle\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use AppBundle\Model\UserInterface as User;

class AdminController extends EasyAdminController
{

    public function createNewEntity()
    {
        $foo = 97896;
        return $this->container->get('fos_user.user_manager')->createUser();
    }

    public function prePersistEntity($entity)
    {
        $foo = 97896;
        if ($entity instanceof User) {
            $this->container->get('fos_user.user_manager')->updateUser($entity, false);
        }
    }

    public function preUpdateEntity($entity)
    {
        $foo = 97896;
        if ($entity instanceof User) {
            $this->container->get('fos_user.user_manager')->updateUser($entity, false);
        }
    }

}
