<?php

namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserFormModel.
 */
class GradeFormModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/^[A-Za-z0-9 _]*[A-Za-z]+[A-Za-z0-9 _]*$/")
     *
     * @var string
     */
    protected $subject;

    /**
     * @Assert\Regex("/[A-Za-z\d\-_\s]+/")
     *
     * @var string
     */
    protected $description;

    /**
     * @param string $name
     */
    public function __construct($subject = null, $description = null)
    {
        $this->subject = $subject;
        $this->description = $description;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
