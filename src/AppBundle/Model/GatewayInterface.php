<?php
namespace AppBundle\Model;
/**
 * Interface GatewayInterface
 * @package AppBundle\Model
 */
interface GatewayInterface
{
    /**
     * @param integer|string|mixed $id
     */
    public function find($id);
    /**
     * @param array $criteria
     * @param array $sort
     * @param integer $limit
     * @param integer $skip
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