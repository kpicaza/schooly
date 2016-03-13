<?php

namespace AppBundle\Handler;

/**
 * ApiHandlerInterface.
 */
interface ApiHandlerInterface
{
    /**
     * Get Entity|Document from repository.
     * 
     * @param string|num $id
     */
    public function get($id);

    /**
     * Insert Entity|Document to repository.
     * 
     * @param array $params
     */
    public function post(array $params);

    /**
     * Update Entity|Document to repository.
     * 
     * @param string|num $id
     * @param array      $params
     */
    public function put($id, array $params);

    /**
     * Delete Entity|Document from repository.
     * 
     * @param string|num
     */
    public function delete($id);
}
