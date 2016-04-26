<?php

namespace AppBundle\Model\Grade;

use AppBundle\Model\FactoryInterface;

/**
 * Class GradeSessionRepository.
 */
class GradeSessionRepository
{
    /**
     * @var GradeSessionGatewayInterface
     */
    private $gateway;
    /**
     * @var FactoryInterface
     */
    private $factory;
    /**
     * @var GradeGateway;
     */
    private $gradeGateway;

    /**
     * @param GradeSessionGatewayInterface $gateway
     * @param FactoryInterface             $factory
     */
    public function __construct(GradeSessionGatewayInterface $gateway, FactoryInterface $factory, GradeGatewayInterface $gradeGateway)
    {
        $this->gateway = $gateway;
        $this->factory = $factory;
        $this->gradeGateway = $gradeGateway;
    }

    /**
     * @param GradeSessionInterface|int $id
     *
     * @return array
     */
    public function find($id)
    {
        $rawGradeSession = $this->gateway->find($id);

        return $this->factory->makeOne($rawGradeSession);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return GradeSessionInterface
     */
    public function findOneBy(array $criteria)
    {
        $gradeSession = $this->gateway->findOneBy($criteria);

        return null === $gradeSession ? null : $this->factory->makeOne($call);
    }

    /**
     * @param array $criteria
     * @param array $sort
     * @param int   $limit
     * @param int   $skip
     *
     * @return array
     */
    public function findBy(array $criteria = array(), $sort = null, $limit = null, $skip = null)
    {
        $rawGradeSessions = $this->gateway->findBy($criteria, $sort, $limit, $skip);

        return $this->factory->makeAll($rawGradeSessions);
    }

    /**
     * @param \DateTime|null $start_date
     * @param \DateTime|null $end_date
     *
     * @return mixed
     */
    public function findNew($id, \DateTime $start_date = null, \DateTime $end_date = null, $formatted = false)
    {
        $grade = $this->gradeGateway->find($id);

        $rawGradeSession = $this->gateway->findNew($grade, $start_date, $end_date);

        return false === $formatted ? $this->factory->makeOne($rawGradeSession) : $rawGradeSession;
    }

    /**
     * @param GradeSessionInterface $rawGrade
     *
     * @return GradeSessionInterface
     */
    public function insert(GradeSessionInterface $rawGradeSession)
    {
        $gradeSession = $this->gateway->insert($rawGradeSession);

        return $this->factory->makeOne($gradeSession);
    }

    /**
     * @param GradeInterface $rawGrade
     *
     * @return bool
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
