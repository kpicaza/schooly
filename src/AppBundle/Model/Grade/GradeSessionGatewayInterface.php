<?php

namespace AppBundle\Model\Grade;

/**
 * Interface GradeGatewayInterface
 * @package AppBundle\Model\Grade
 */
interface GradeSessionGatewayInterface
{
    public function findNew(GradeInterface $grade, \DateTime $start_date = null, \DateTime $end_date = null);
    public function insert(GradeSessionInterface $GradeSession);
    public function update();
    public function remove($id);

}