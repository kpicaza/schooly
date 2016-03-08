<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserGateway.
 */
class UserGateway extends EntityRepository implements UserGatewayInterface
{
    /**
     * @param User $user
     *
     * @return User
     */
    public function apiInsert(User $user)
    {
        $user
            ->setEnabled(true)
            ->setExpired(false)
            ->setLocked(false)
            ->addRole('read')
            ->addRole('edit')
            ->addRole('ROLE_USER')
            ->addRole('ROLE_API_USER')
        ;

        return self::insert($user);
    }

    /**
     * @return type
     */
    public function findNew()
    {
        return ORMUser::fromArray();
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function insert(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    /**
     * Update user.
     */
    public function update()
    {
        $this->_em->flush();
    }

    /**
     * @param User $user
     */
    public function remove(User $user)
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }
}
