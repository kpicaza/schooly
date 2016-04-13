<?php

namespace AppBundle\Model\Grade;

use AppBundle\Model\Grade\GradeSessionInterface;
use AppBundle\Model\FactoryInterface;

/**
 * Class GradeSessionFactory
 * @package AppBundle\Model\Grade
 */
class GradeSessionFactory implements FactoryInterface
{
    /**
     * @param array $rawGradeSessions
     * @param array $params
     * @return array
     */
    public function makeAll(array $rawGradeSessions, array $params = array())
    {
        $GradeSessions = array();

        foreach ($rawGradeSessions as $rawGradeSession) {
            $GradeSessions[] = $this->make($rawGradeSession, $params);
        }

        return $GradeSessions;
    }
    /**
     * @param GradeSessionInterface $rawGradeSession
     * @return GradeSessionInterface
     */
    public function makeOne($rawGradeSession, array $params = array())
    {
        return $this->make($rawGradeSession, $params);
    }
    /**
     * @param GradeSessionInterface $rawUser
     * @return GradeSessionInterface
     */
    public function make($rawGradeSession, array $params = array())
    {
        if (!$rawGradeSession instanceof GradeSessionInterface) {
            return null;
        }

        // You can format object, in this case we left it to return as raw object, feedback is welcome!
        return $rawGradeSession;
    }
}