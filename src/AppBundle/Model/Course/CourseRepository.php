<?php
namespace AppBundle\Model\Course;
use AppBundle\Model\Course\CourseGatewayInterface;
use AppBundle\Model\FactoryInterface;
/**
 * CourseRepository.
 */
class CourseRepository
{
    /**
     * @var \AppBundle\Model\Course\CourseGatewayInterface
     */
    private $gateway;
    /**
     * @var \AppBundle\Model\FactoryInterface
     */
    private $factory;
    /**
     * @param \AppBundle\Model\Course\CourseGatewayInterface $gateway
     * @param \AppBundle\Model\FactoryInterface $factory
     */
    public function __construct(CourseGatewayInterface $gateway, FactoryInterface $factory)
    {
        $this->gateway = $gateway;
        $this->factory = $factory;
    }
    /**
     * @param Course|int $id
     *
     * @return Course
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
     * @return Course
     */
    public function findOneBy(array $criteria, array $orderBy = array())
    {
        $course = $this->gateway->findOneBy($criteria, $orderBy);
        if (null === $course) {
            return null;
        }
        
        return $this->factory->makeOne($course);
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
        return $this->gateway->findBy($criteria, $sort, $limit, $skip);
    }
    /**
     * @return CourseInterface
     */
    public function findNew()
    {
        return $this->gateway->findNew();
    }
    /**
     * @param Course $rawCourse
     *
     * @return type
     */
    public function insert(CourseInterface $rawCourse)
    {
        $course = $this->gateway->insert($rawCourse);
        
        return $this->factory->makeOne($course);
    }
    /**
     * @param Course $rawCourse
     *
     * @return type
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
}