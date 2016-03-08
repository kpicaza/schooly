<?php

namespace AppBundle\Entity;

/**
 * UserGateway.
 */
interface UserGatewayInterface
{
    /**
     * @param User $user
     *
     * @return User
     */
    public function apiInsert(User $user);

    /**
     * @return type
     */
    public function findNew();

    /**
     * @param User $user
     *
     * @return User
     */
    public function insert(User $user);

    /**
     * Update user.
     */
    public function update();

    /**
     * @param User $user
     */
    public function remove(User $user);
}
