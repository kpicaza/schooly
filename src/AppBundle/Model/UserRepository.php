<?php

namespace AppBundle\Model;

use AppBundle\Model\UserGatewayInterface;
use AppBundle\Model\UserFactoryInterface;

/**
 * UserRepository.
 */
class UserRepository
{

    /**
     * @var \AppBundle\Model\UserGatewayInterface
     */
    private $gateway;

    /**
     * @var \AppBundle\Model\UserFactoryInterface
     */
    private $factory;

    /**
     * @param \AppBundle\Model\UserGatewayInterface $gateway
     * @param \AppBundle\Model\UserFactoryInterface $factory
     */
    public function __construct(UserGatewayInterface $gateway, UserFactoryInterface $factory)
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
            return null;
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
    public function insert(UserInterface $user)
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
     * @param $id
     */
    public function remove($id)
    {
        $this->gateway->remove($id);
    }

    /**
     * @param $id
     *
     * @return User
     */
    public function parse($id)
    {
        $rawUser = $this->gateway->find($id);
        
        return $this->factory->makeOne($rawUser);
    }
}
