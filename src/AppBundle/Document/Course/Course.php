<?php
namespace AppBundle\Document\Course;
use AppBundle\Model\Course\CourseInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\UniqueIndex;
/**
 * Course.
 * 
 * @MongoDB\Document(repositoryClass="AppBundle\Document\Course\CourseGateway")
 */
class Course implements CourseInterface
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;
    /**
     * @var string
     * 
     * @MongoDB\String
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
     * @return \AppBundle\Document\Course\Course
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
}
