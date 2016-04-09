<?php

namespace AppBundle\Model\Grade;

use AppBundle\Model\Grade\GradeInterface;
use AppBundle\Model\FactoryInterface;

/**
 * Class GradeFactory
 * @package AppBundle\Model\Course
 */
class GradeFactory implements FactoryInterface
{
    /**
     * @param array $rawCourses
     * @param array $params
     * @return array
     */
    public function makeAll(array $rawCourses, array $params = array())
    {
        $grades = array();

        foreach ($rawGrades as $rawGrade) {
            $grades[] = $this->make($rawGrade, $params);
        }

        return $grades;
    }
    /**
     * @param GradeInterface $rawGrade
     * @return GradeInterface
     */
    public function makeOne($rawGrade, array $params = array())
    {
        return $this->make($rawGrade, $params);
    }
    /**
     * @param GradeInterface $rawUser
     * @return GradeInterface
     */
    public function make($rawGrade, array $params = array())
    {
        if (!$rawGrade instanceof GradeInterface) {
            return null;
        }

        // You can format object, in this case we left it to return as raw object, feedback is welcome!
        return $rawGrade;
    }
}