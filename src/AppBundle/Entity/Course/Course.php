<?php

namespace AppBundle\Entity\Course;

use AppBundle\Model\Course\CourseInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Course.
 * 
 * @ORM\Entity
 * @ORM\Table(name = "course")
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Course\CourseGateway")
 * @Hateoas\Relation(
 *      "get_courses",
 *      href = @Hateoas\Route(
 *          "get_courses"
 *      )
 * )
 * @Hateoas\Relation(
 *      "post_course",
 *      href = @Hateoas\Route(
 *          "post_course"
 *      ),
 *      exclusion = @Hateoas\Exclusion(
 *          excludeIf = "expr(not is_granted(['ROLE_TEACHER']))"
 *      )
 * )
 * @Hateoas\Relation(
 *      "post_course_picture",
 *      href = @Hateoas\Route(
 *          "post_course_picture",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(
 *          excludeIf = "expr(not is_granted(['ROLE_TEACHER']))"
 *      )
 * )
 * @Hateoas\Relation(
 *      "get_course",
 *      href = @Hateoas\Route(
 *          "get_course",
 *          parameters = { "id" = "expr(object.getId())" }
 *      )
 * )
 * @Hateoas\Relation(
 *      "put_course",
 *      href = @Hateoas\Route(
 *          "put_course",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(
 *          excludeIf = "expr(not is_granted(['ROLE_TEACHER']))"
 *      )
 * )
 * @Hateoas\Relation(
 *      "delete_course",
 *      href = @Hateoas\Route(
 *          "delete_course",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(
 *          excludeIf = "expr(not is_granted(['ROLE_ADMIN']))"
 *      )
 * ) */
class Course implements CourseInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Course name.
     * 
     * @var string
     * 
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
    /**
     * Course description.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    protected $description = null;
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $enabled = false;
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="course_image", fileNameProperty="imageName")
     * 
     * @var File
     */
    protected $imageFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $imageName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $updatedAt;
    /**
     * @return integer|string
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
     * @param string $name
     * @return \AppBundle\Entity\Course\Course
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @param string $description
     * @return \AppBundle\Entity\Course\Course
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
    /**
     * Get enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return (bool) $this->enabled;
    }
    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
    /**
     * Get enabled
     *
     * @return boolean
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        
        return $this;
    }
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Course
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }
    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     *
     * @return Product
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set updatedAt
     *
     * @param date $updatedAt
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return date $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
