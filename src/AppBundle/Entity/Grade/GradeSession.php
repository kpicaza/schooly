<?php

namespace AppBundle\Entity\Grade;

use AppBundle\Model\Grade\GradeSessionInterface;
use AppBundle\Model\Grade\GradeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class GradeSession
 * @package AppBundle\Entity\Grade
 *
 * @ORM\Entity
 * @ORM\Table(name = "grade_session")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Grade\GradeSessionGateway")
 */
class GradeSession implements GradeSessionInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $startDate;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $endDate;
    /**
     * @ORM\ManyToOne(targetEntity="Grade")
     * @ORM\JoinColumn(name="grade_id", referencedColumnName="id")
     *
     * @var GradeInterface
     */
    protected $grade;


    public function __construct(GradeInterface $grade = null, \DateTime $start_date = null, \DateTime $end_date = null)
    {
        $this->grade = $grade;
        $this->startDate = $start_date;
        $this->endDate = $end_date;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return GradeSession
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return GradeSession
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set grade
     *
     * @param GradeInterface $grade
     *
     * @return GradeSession
     */
    public function setGrade(GradeInterface $grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return GradeInterface
     */
    public function getGrade()
    {
        return $this->grade;
    }
}
