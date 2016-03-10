<?php
namespace AppBundle\Handler;
use AppBundle\Model\UserInterface;
/**
 * ApiHandleInterface.
 */
interface ApiUserHandlerInterface
{
    /**
     * Get user from repository.
     * 
     * @param User $user
     */
    public function get(UserInterface $user);
    /**
     * Insert User to repository.
     * 
     * @param array $params
     */
    public function post(array $params);
}