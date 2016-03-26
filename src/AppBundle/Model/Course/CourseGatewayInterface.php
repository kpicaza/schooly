<?php
namespace AppBundle\Model\Course;
/**
 * CourseGatewayInterface.
 */
interface CourseGatewayInterface
{
    /**
     * @param type $id
     */
    public function find($id);
    /**
     * @param array $criteria
     * @param array $sort
     * @param integer $limit
     * @param integer $skip
     */
    public function findBy(array $criteria, array $sort = null, $limit = null, $skip = null);
    /**
     * @param array $criteria
     */
    public function findOneBy(array $criteria);
    public function findNew();
}