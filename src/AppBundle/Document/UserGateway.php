<?php

namespace AppBundle\Document;

use AppBundle\Model\UserInterface;
use AppBundle\Model\UserGatewayInterface;
use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * UserGateway.
 */
class UserGateway extends DocumentRepository implements UserGatewayInterface
{
    /**
     * @param User $user
     *
     * @return User
     */
    public function apiInsert(UserInterface $user)
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
        return User::fromArray();
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function insert(UserInterface $user)
    {
        $this->dm->persist($user);
        $this->dm->flush();

        return $user;
    }

    /**
     * Update user.
     */
    public function update()
    {
        $this->dm->flush();
    }

    /**
     * @param User $user
     */
    public function remove(UserInterface $user)
    {
        $this->dm->remove($user);
        $this->dm->flush();
    }
}
