<?php

namespace AppBundle\Model\Grade;

use AppBundle\Model\FactoryInterface;

/**
 * Class GradeFactory.
 */
class GradeFactory implements FactoryInterface
{
    /**
     * @param array $rawGrades
     * @param array $params
     *
     * @return array
     */
    public function makeAll(array $rawGrades, array $params = array())
    {
        $grades = array();

        foreach ($rawGrades as $rawGrade) {
            $grades[] = $this->make($rawGrade, $params);
        }

        return $grades;
    }
    /**
     * @param GradeInterface $rawGrade
     *
     * @return GradeInterface
     */
    public function makeOne($rawGrade, array $params = array())
    {
        return $this->make($rawGrade, $params);
    }
    /**
     * @param GradeInterface $rawUser
     *
     * @return GradeInterface
     */
    public function make($rawGrade, array $params = array())
    {
        if (!$rawGrade instanceof GradeInterface) {
            return;
        }

        // You can format object, in this case we left it to return as raw object, feedback is welcome!
        return $rawGrade;
    }
}
