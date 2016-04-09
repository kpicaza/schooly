<?php

namespace AppBundle\Model\User;

use AppBundle\Model\User\UserInterface;
use AppBundle\Model\FactoryInterface;

/**
 * Factory implements FactoryInterface.
 */
class UserFactory implements FactoryInterface
{
    /**
     * @param array $rawUsers
     * @param array $params
     * @return array
     */
    public function makeAll(array $rawUsers, array $params = array())
    {
        $users = array();

        foreach ($rawUsers as $rawUser) {
            $users[] = $this->make($rawUser, $params);
        }

        return $users;
    }
    /**
     * @param UserInterface $rawUser
     * @return UserInterface
     */
    public function makeOne($rawUser, array $params = array())
    {
        return $this->make($rawUser, $params);
    }
    /**
     * @param UserInterface $rawUser
     * @return UserInterface
     */
    public function make($rawUser, array $params = array())
    {
        // You can format object, in this case we left it to return as raw object, feedback is welcome!
        return $rawUser;
    }
}