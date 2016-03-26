<?php
namespace AppBundle\Handler;
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
    public function get($id);
    /**
     * Insert User to repository.
     * 
     * @param array $params
     */
    public function post(array $params);
    /**
     * Update User from repository.
     * 
     * @param $id
     * @param array $params
     */
    public function put($id, array $params);
}