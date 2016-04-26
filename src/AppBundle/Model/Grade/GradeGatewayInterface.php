<?php

namespace AppBundle\Model\Grade;

use AppBundle\Model\GatewayInterface;

/**
 * Interface GradeGatewayInterface.
 */
interface GradeGatewayInterface extends GatewayInterface
{
    public function findNew($subject = null, $description = null);
    public function insert(GradeInterface $grade);
    public function update();
    public function remove($id);
}
