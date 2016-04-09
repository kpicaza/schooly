<?php
namespace AppBundle\Model\Grade;

use AppBundle\Model\Grade\GradeGatewayInterface;
use AppBundle\Model\FactoryInterface;

/**
 * Class GradeRepository
 * @package AppBundle\Model\Grade
 */
class GradeRepository
{
    /**
     * @var GradeGatewayInterface
     */
    private $gateway;
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @param GradeGatewayInterface $gateway
     * @param FactoryInterface $factory
     */
    public function __construct(GradeGatewayInterface $gateway, FactoryInterface $factory)
    {
        $this->gateway = $gateway;
        $this->factory = $factory;
    }

    /**
     * @param GradeInterface|int $id
     *
     * @return GradeInterface
     */
    public function find($id)
    {
        $rawGrade = $this->gateway->find($id);

        return $this->factory->makeOne($rawGrade);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return GradeInterface
     */
    public function findOneBy(array $criteria)
    {
        $grade = $this->gateway->findOneBy($criteria);

        return null === $grade ? null : $this->factory->makeOne($grade);
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
        return $this->gateway->findBy($criteria, $sort, $limit, $skip);
    }

    /**
     * @param null $subject
     * @param null $description
     * @return GradeInterface
     */
    public function findNew($subject = null, $description = null)
    {
        return $this->gateway->findNew($subject, $description);
    }

    /**
     * @param GradeInterface $rawGrade
     *
     * @return GradeInterface
     */
    public function insert(GradeInterface $rawGrade)
    {
        $grade = $this->gateway->insert($rawGrade);

        return $this->factory->makeOne($grade);
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
     * @param GradeInterface $course
     * @param $imageFile
     * @param $imageName
     * @return GradeInterface
     */
    public function addFile(GradeInterface $course, $imageFile, $imageName)
    {
        $course
            ->setImageFile($imageFile)
            ->setImageName($imageName)
        ;

        $this->update();

        return $course;
    }

    /**
     * @param $id
     */
    public function remove($id)
    {
        $this->gateway->remove($id);
    }
}