<?php

namespace AppBundle\Model\Course;

use AppBundle\Model\Course\CourseInterface;
use AppBundle\Model\FactoryInterface;

/**
 * Factory implements FactoryInterface.
 */
class CourseFactory implements FactoryInterface
{
    /**
     * @param array $rawCourses
     * @param array $params
     * @return array
     */
    public function makeAll(array $rawCourses, array $params = array())
    {
        $courses = array();

        foreach ($rawCourses as $rawCourse) {
            $courses[] = $this->make($rawCourse, $params);
        }

        return $courses;
    }
    /**
     * @param CourseInterface $rawUser
     * @return CourseInterface
     */
    public function makeOne($rawCourse, array $params = array())
    {
        return $this->make($rawCourse, $params);
    }
    /**
     * @param CourseInterface $rawUser
     * @return CourseInterface
     */
    public function make($rawCourse, array $params = array())
    {
        if (!$rawCourse instanceof CourseInterface) {
            return null;
        }

        // You can format object, in this case we left it to return as raw object, feedback is welcome!
        return $rawCourse;
    }
}