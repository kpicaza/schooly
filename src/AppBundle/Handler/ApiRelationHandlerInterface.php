<?php

namespace AppBundle\Handler;

/**
 * ApiHandlerInterface.
 */
interface ApiRelationHandlerInterface
{
    public function getList($id, array $criteria, array $sort = null, $limit = null, $skip = null);
    public function get($id, $session_id);
    public function post($id, array $params);
    public function put($id, $session_id, array $params);
}
