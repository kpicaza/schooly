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
     * @Assert\Regex("/^[A-Za-z0-9 _]*[A-Za-z]+[A-Za-z0-9 _]*$/")
     *
     * @var string
     */
    protected $name;

    /**
     * @Assert\Regex("/[A-Za-z\d\-_\s]+/")
     *
     * @var string
     */
    protected $description;

    /**
     * @param type $name
     */
    public function __construct($name = null, $description = null)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
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
