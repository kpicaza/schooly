<?php
namespace AppBundle\Model\User;
use AppBundle\Model\User\UserGatewayInterface;
use AppBundle\Model\FactoryInterface;
/**
 * UserRepository.
 */
class UserRepository
{
    /**
     * @var \AppBundle\Model\User\UserGatewayInterface
     */
    private $gateway;
    /**
     * @var \AppBundle\Model\FactoryInterface
     */
    private $factory;
    /**
     * @param \AppBundle\Model\User\UserGatewayInterface $gateway
     * @param \AppBundle\Model\FactoryInterface $factory
     */
    public function __construct(UserGatewayInterface $gateway, FactoryInterface $factory)
    {
        $this->gateway = $gateway;
        $this->factory = $factory;
    }
    /**
     * 
     * @param array $criteria
     * @param array $sort
     * @param integer $limit
     * @param integer $skip
     * @return array
     */
    public function findBy(array $criteria = array(), $sort = null, $limit = null, $skip = null)
    {
        $rawUsers = $this->gateway->findBy($criteria, $sort, $limit, $skip);
        
        return $this->factory->makeAll($rawUsers);
    }
    /**
     * @param User|int $id
     *
     * @return User
     */
    public function find($id)
    {
        $rawUser = $this->gateway->find($id);
        
        return $this->factory->makeOne($rawUser);
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
        $rawUser = $this->gateway->findNew();
        
        return $this->factory->makeOne($rawUser);
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