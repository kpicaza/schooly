<?php
/**
 * Created by PhpStorm.
 * User: kpicaza
 * Date: 9/04/16
 * Time: 22:41
 */

namespace AppBundle\Entity\Grade;


use AppBundle\Model\Grade\GradeInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Grade
 *
 * @ORM\Entity
 * @ORM\Table(name = "grade")
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Grade\GradeGateway")
 *
 * @package AppBundle\Entity\Grade
 */
class Grade implements GradeInterface
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
    protected $subject;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    protected $description;
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $enabled = false;
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="grade_image", fileNameProperty="imageName")
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
     * Grade constructor.
     * @param null $subject
     * @param null $description
     */
    public function __construct($subject = null, $description = null)
    {
        $this->subject = $subject;
        $this->description = $description;
    }

    /**
     * @return integer|string
     */
    public function getId()
    {
        return $this->id;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

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
     * @return $this
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
     * @return $this
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
     * @param \DateTime $updatedAt
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
     * @return \DateTime $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}