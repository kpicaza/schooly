<?php
namespace AppBundle\Document\User;
use AppBundle\Model\User\UserInterface;
use AppBundle\Model\User\UserGatewayInterface;
use Doctrine\ODM\MongoDB\DocumentRepository;
/**
 * UserGateway.
 */
class UserGateway extends DocumentRepository implements UserGatewayInterface
{
    /**
     * @param type $id
     */
    public function find($id)
    {
        return parent::find($id);
    }
    /**
     * @param array $criteria
     * @param array $sort
     * @param integer $limit
     * @param integer $skip
     */
    public function findBy(array $criteria, array $sort = null, $limit = null, $skip = null)
    {
        return parent::findBy($criteria, $sort, $limit, $skip);
    }
    /**
     * @param array $criteria
     */
    public function findOneBy(array $criteria)
    {
        return parent::findOneBy($criteria);
    }
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
     * @param $id
     */
    public function remove($id)
    {
        $user = $this->find($id);
        $this->dm->remove($user);
        $this->dm->flush();
    }
}
