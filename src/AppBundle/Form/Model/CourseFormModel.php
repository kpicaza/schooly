<?php

namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserFormModel.
 */
class CourseFormModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/[A-Za-z\d\-_\s]+/")
     *
     * @var string
     */
    protected $name;

    /**
     * @param type $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
