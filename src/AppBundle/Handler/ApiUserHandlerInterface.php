<?php

namespace AppBundle\Handler;

use AppBundle\Entity\User;

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
    public function get(User $user);

    /**
     * Insert User to repository.
     * 
     * @param array $params
     */
    public function post(array $params);

    /**
     * Update User to repository.
     * 
     * @param string|num $id
     * @param array      $params
     */
    public function put($id, array $params);

    /**
     * Delete User from repository.
     * 
     * @param User $user
     */
    public function delete(User $user);
}
