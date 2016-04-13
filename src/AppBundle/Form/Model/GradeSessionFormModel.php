<?php

namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserFormModel.
 */
class GradeSessionFormModel
{
    /**
     * @Assert\NotNull()
     * @Assert\DateTime()
     *
     * @var string
     */
    protected $start_date;

    /**
     * @Assert\DateTime()
     *
     * @var string
     */
    protected $end_date;

    /**
     * GradeSessionFormModel constructor.
     * @param \DateTime|null $start_date
     * @param \DateTime|null $end_date
     */
    public function __construct(\DateTime $start_date = null, \DateTime $end_date = null)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function setStartDate($start_date)
    {
        if ($this->validateDate($start_date)) {
            $this->start_date = new \DateTime($start_date);
        } else {
            $this->start_date = $start_date;
        }
    }

    public function getStartDate()
    {
        return $this->start_date;
    }

    public function setEndDate($end_date)
    {
        if ($this->validateDate($end_date)) {
            $this->start_date = new \DateTime($end_date);
        } else {
            $this->end_date = $end_date;
        }
    }

    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     *
     * @param string $date_string
     * @return string
     */
    function validateDate($date_string)
    {
        return (bool)strtotime($date_string);
    }

}
