<?php
namespace AppBundle\Model\Grade;

use AppBundle\Model\Grade\GradeSessionGatewayInterface;
use AppBundle\Model\FactoryInterface;

/**
 * Class GradeSessionRepository
 * @package AppBundle\Model\Grade
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
     * @var GradeRepository;
     */
    private $gradeRepository;

    /**
     * @param GradeSessionGatewayInterface $gateway
     * @param FactoryInterface $factory
     */
    public function __construct(GradeSessionGatewayInterface $gateway, FactoryInterface $factory, GradeRepository $gradeRepository)
    {
        $this->gateway = $gateway;
        $this->factory = $factory;
        $this->gradeRepository = $gradeRepository;
    }

    /**
     * @param GradeSessionInterface|int $id
     *
     * @return GradeSessionInterface
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
     *
     * @param array $criteria
     * @param array $sort
     * @param integer $limit
     * @param integer $skip
     * @return array
     */
    public function findBy(array $criteria = array(), $sort = null, $limit = null, $skip = null)
    {
        $rawGradeSession = $this->gateway->findBy($criteria, $sort, $limit, $skip);

        return $this->factory->makeAll($rawGradeSession);
    }

    /**
     * @param \DateTime|null $start_date
     * @param \DateTime|null $end_date
     * @return mixed
     */
    public function findNew($id, \DateTime $start_date = null, \DateTime $end_date = null)
    {
        $grade = $this->gradeRepository->find($id);

        $rawGradeSession = $this->gateway->findNew($grade, $start_date, $end_date);

        return $this->factory->makeOne($rawGradeSession);
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