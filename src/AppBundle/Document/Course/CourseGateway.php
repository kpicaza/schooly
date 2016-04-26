<?php

namespace AppBundle\Document\Course;

use AppBundle\Model\Course\CourseGatewayInterface;
use Doctrine\ODM\MongoDB\DocumentRepository;

class CourseGateway extends DocumentRepository implements CourseGatewayInterface
{
    /**
     * @param type $id
     */
    public function find($id, $lockMode = 0, $lockVersion = null)
    {
        return parent::find($id, $lockMode, $lockVersion);
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
     *
     * @return type
     */
    public function insert($course)
    {
        $this->dm->persist($course);
        $this->dm->flush();

        return $course;
    }
    /**
     * Update course.
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
