<?php

namespace AppBundle\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use AppBundle\Model\User\UserInterface as User;

class AdminController extends EasyAdminController
{

    public function createNewEntity()
    {
        return $this->container->get('fos_user.user_manager')->createUser();
    }

    public function prePersistEntity($entity)
    {
        if ($entity instanceof User) {
            $this->container->get('fos_user.user_manager')->updateUser($entity, false);
        }
    }

    public function preUpdateEntity($entity)
    {
        if ($entity instanceof User) {
            $this->container->get('fos_user.user_manager')->updateUser($entity, false);
        }
    }

}
