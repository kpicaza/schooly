<?php

namespace AppBundle\Model;

/**
 * UserFactoryInterface.
 */
interface UserFactoryInterface
{
    /**
     * @param \AppBundle\Model\UserInterface $rawUser
     *
     * @return \AppBundle\Model\UserInterface
     */
    public function makeOne(UserInterface $rawUser);

    /**
     * @param array $rawUsers
     *
     * @return array
     */
    public function makeAll(array $rawUsers);

    /**
     * @param \AppBundle\Model\UserInterface $rawUser
     *
     * @return \AppBundle\Model\UserInterface
     */
    public function make(UserInterface $rawUser);
}
