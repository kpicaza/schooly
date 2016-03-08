<?php

namespace AppBundle\Entity;

class UserFactory implements UserFactoryInterface
{
    /**
     * @param \AppBundle\Entity\User $rawUser
     *
     * @return \AppBundle\Entity\User
     */
    public function makeOne(User $rawUser)
    {
        return $this->make($rawUser);
    }

    /**
     * @param array $rawUsers
     *
     * @return array
     */
    public function makeAll(array $rawUsers)
    {
        foreach ($rawUsers as $rawUser) {
            $users[$rawUser->getId()] = $this->make($rawUser);
        }

        return $users;
    }

    /**
     * @param \AppBundle\Entity\User $rawUser
     *
     * @return \AppBundle\Entity\User
     */
    public function make(User $rawUser)
    {
        // You can format object, in this case we left it to return as raw object, feedback is welcome!
        return $rawUser->toApi($rawUser);
    }
}
