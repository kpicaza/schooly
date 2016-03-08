<?php

namespace AppBundle\Entity;

interface UserFactoryInterface
{
    /**
     * @param \AppBundle\Entity\User $rawUser
     *
     * @return \AppBundle\Entity\User
     */
    public function makeOne(User $rawUser);

    /**
     * @param array $rawUsers
     *
     * @return array
     */
    public function makeAll(array $rawUsers);

    /**
     * @param \AppBundle\Entity\User $rawUser
     *
     * @return \AppBundle\Entity\User
     */
    public function make(User $rawUser);
}
