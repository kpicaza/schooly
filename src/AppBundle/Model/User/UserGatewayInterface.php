<?php

namespace AppBundle\Model\User;

/**
 * UserGateway.
 */
interface UserGatewayInterface
{
    /**
     * @param type $id
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
     * @param User $user
     *
     * @return User
     */
    public function apiInsert(UserInterface $user);

    /**
     * @return type
     */
    public function findNew();
    /**
     * @param User $user
     *
     * @return User
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
