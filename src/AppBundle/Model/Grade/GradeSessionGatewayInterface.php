<?php

namespace AppBundle\Model\Grade;

/**
 * Interface GradeGatewayInterface.
 */
interface GradeSessionGatewayInterface
{
    public function findNew(GradeInterface $grade, \DateTime $start_date = null, \DateTime $end_date = null);
    public function insert(GradeSessionInterface $GradeSession);
    public function update();
    public function remove($id);
}
