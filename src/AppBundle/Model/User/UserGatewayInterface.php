<?php

namespace AppBundle\Model\User;
use AppBundle\Model\GatewayInterface;

/**
 * Interface UserGatewayInterface
 * @package AppBundle\Model\User
 */
interface UserGatewayInterface extends GatewayInterface
{
    /**
     * @param integer|string|UserInterface $id
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
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function apiInsert(UserInterface $user);

    /**
     * @return UserInterface
     */
    public function findNew();
    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function insert(UserInterface $user);
    /**
     * Update User
     */
    public function update();
    /**
     * @param $id
     */
    public function remove($id);
}
