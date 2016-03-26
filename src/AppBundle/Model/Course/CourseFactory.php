<?php

namespace AppBundle\Model;

use AppBundle\Model\UserInterface;
use AppBundle\Model\UserFactoryInterface;
/**
 * UserFactory implements UserFactoryInterface.
 */
class UserFactory implements UserFactoryInterface
{
    /**
     * @param \AppBundle\Entity\User $rawUser
     *
     * @return \AppBundle\Entity\User
     */
    public function makeOne(UserInterface $rawUser)
    {
        return $this->make($rawUser);
    }
    /**
     * @param \AppBundle\Entity\User $rawUser
     *
     * @return \AppBundle\Entity\User
     */
    public function make(UserInterface $rawUser)
    {
        // You can format object, in this case we left it to return as raw object, feedback is welcome!
        return $rawUser;
    }
}