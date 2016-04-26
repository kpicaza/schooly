<?php

namespace AppBundle\Handler;

/**
 * ApiHandlerInterface.
 */
interface ApiHandlerInterface
{
    /**
     * Get object list from repository.
     * 
     * @param array $criteria
     * @param array $sort
     * @param int   $limit
     * @param int   $skip
     */
    public function getList(array $criteria, array $sort = null, $limit = null, $skip = null);
    /**
     * Get object from repository.
     * 
     * @param int $id
     */
    public function get($id);
    /**
     * Insert object to repository.
     * 
     * @param array $params
     */
    public function post(array $params);
    /**
     * Update object from repository.
     * 
     * @param $id
     * @param array $params
     */
    public function put($id, array $params);
}
