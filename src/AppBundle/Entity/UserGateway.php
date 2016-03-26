<?php
namespace AppBundle\Entity;
use AppBundle\Model\User\UserInterface;
use AppBundle\Model\User\UserGatewayInterface;
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
    public function apiInsert(UserInterface $user)
    {
        $user
            ->setEnabled(true)
            ->setExpired(false)
            ->setLocked(false)
            ->addRole('read')
            ->addRole('view')
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
        $this->_em->persist($user);
        $this->_em->flush();
        return $user;
    }
    /**
     * Update User.
     */
    public function update()
    {
        $this->_em->flush();
    }
    /**
     * Delete User.
     * 
     * @param $id
     */
    public function remove($id)
    {
        $user = $this->find($id);
        
        $this->_em->remove($user);
        $this->_em->flush();
    }
}
