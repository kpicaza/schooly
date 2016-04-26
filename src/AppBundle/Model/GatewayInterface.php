<?php

namespace AppBundle\Model;

/**
 * Interface GatewayInterface.
 */
interface GatewayInterface
{
    /**
     * @param int|string|mixed $id
     */
    public function find($id);
    /**
     * @param array $criteria
     * @param array $sort
     * @param int   $limit
     * @param int   $skip
     */
    public function findBy(array $criteria, array $sort = null, $limit = null, $skip = null);
    /**
     * @param array $criteria
     */
    public function findOneBy(array $criteria);
    /**
     * @return mixed
     */
    public function findNew();
}
