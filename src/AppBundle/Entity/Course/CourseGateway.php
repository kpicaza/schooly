<?php
namespace AppBundle\Entity\Course;
use AppBundle\Model\Course\CourseGatewayInterface;
use Doctrine\ORM\EntityRepository;
class CourseGateway extends EntityRepository implements CourseGatewayInterface
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
     * @return Course
     */
    public function findNew()
    {
        return new Course();
    }
    /**
     * @param type $course
     * @return type
     */
    public function insert($course)
    {
        $this->_em->persist($course);
        $this->_em->flush();
        
        return $course;
    }
    /**
     * Update course.
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
        $user = $this->find($id);
        $this->_em->remove($user);
        $this->_em->flush();
    }
}
