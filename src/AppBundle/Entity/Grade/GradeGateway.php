<?php

namespace AppBundle\Entity\Grade;

use AppBundle\Model\Grade\GradeGatewayInterface;
use AppBundle\Model\Grade\GradeInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class GradeGateway.
 */
class GradeGateway extends EntityRepository implements GradeGatewayInterface
{
    /**
     * @param string|int|Grade $id
     */
    public function find($id)
    {
        return parent::find($id);
    }
    /**
     * @param array $criteria
     * @param array $sort
     * @param int   $limit
     * @param int   $skip
     */
    public function findBy(array $criteria, array $sort = null, $limit = null, $skip = null)
    {
        return parent::findBy($criteria, $sort, $limit, $skip);
    }
    /**
     * @param array $criteria
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return parent::findOneBy($criteria);
    }
    /**
     * @return Grade
     */
    public function findNew($subject = null, $description = null)
    {
        return new Grade($subject, $description);
    }

    /**
     * @param GradeInterface $grade
     *
     * @return GradeInterface
     */
    public function insert(GradeInterface $grade)
    {
        $this->_em->persist($grade);
        $this->_em->flush();

        return $grade;
    }
    /**
     * Update Grade.
     */
    public function update()
    {
        $this->_em->flush();
    }
    /**
     * @param $id
     */
    public function remove($id)
    {
        $grade = $this->find($id);
        $this->_em->remove($grade);
        $this->_em->flush();
    }
}
