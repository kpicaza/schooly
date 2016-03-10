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
     * @param \AppBundle\Model\UserInterface $rawUser
     *
     * @return \AppBundle\Model\UserInterface
     */
    public function make(UserInterface $rawUser);
}