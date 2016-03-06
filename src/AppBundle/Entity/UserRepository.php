<?php

namespace AppBundle\Entity;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * UserRepository.
 */
class UserRepository
{
    /**
     * @var \AppBundle\Entity\UserGateway
     */
    private $gateway;

    /**
     * @var \AppBundle\Entity\UserFactory
     */
    private $factory;

    /**
     * @param \AppBundle\Entity\UserGateway $gateway
     * @param \AppBundle\Entity\UserFactory $factory
     */
    public function __construct(UserGateway $gateway, UserFactory $factory)
    {
        $this->gateway = $gateway;
        $this->factory = $factory;
    }

    /**
     * @param User|int $id
     *
     * @return User
     */
    public function find($id)
    {
        return $this->gateway->find($id);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $users = $this->gateway->findAll();

        return null === $users ? array() : $this->factory->makeAll($users);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int   $limit
     * @param int   $offset
     *
     * @return array
     */
    public function findBy(array $criteria = array(), array $orderBy = array('created_at' => 'ASC'), $limit = null, $offset = null)
    {
        $users = $this->gateway->findBy($criteria, $orderBy, $limit, $offset);

        return null === $users ? array() : $this->factory->makeAll($users);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return User
     *
     * @throws NotFoundHttpException
     */
    public function findOneBy(array $criteria, array $orderBy = array())
    {
        $user = $this->gateway->findOneBy($criteria, $orderBy);

        if (null === $user) {
            throw new NotFoundHttpException('Entity User not Found');
        }

        return $this->factory->makeOne($user);
    }

    /**
     * @return User
     */
    public function findNew()
    {
        return $this->gateway->findNew();
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function insert(User $user)
    {
        $rawUser = $this->gateway->apiInsert($user);

        return $this->factory->makeOne($rawUser);
    }

    /**
     * 
     */
    public function update()
    {
        return $this->gateway->update();
    }

    /**
     * @param User $user
     */
    public function remove(User $user)
    {
        $this->gateway->remove($user);
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function parse(User $user)
    {
        return $this->factory->makeOne($user);
    }
}
