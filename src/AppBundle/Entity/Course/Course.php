<?php

namespace AppBundle\Entity\Course;

use AppBundle\Model\Course\CourseInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Course.
 * 
 * @ORM\Entity
 * @ORM\Table(name = "course")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Course\CourseGateway")
 */
class Course implements CourseInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @return type
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param type $name
     * @return \AppBundle\Entity\Course\Course
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

}
