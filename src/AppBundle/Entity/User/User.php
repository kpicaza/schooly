<?php

// src/AppBundle/Entity/User.php

namespace AppBundle\Entity\User;

use AppBundle\Model\User\UserInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 * @ORM\Table(name = "user")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\User\UserGateway")
 * @Hateoas\Relation(
 *      "get_users",
 *      href = @Hateoas\Route(
 *          "get_users"
 *      )
 * )
 * @Hateoas\Relation(
 *      "post_user",
 *      href = @Hateoas\Route(
 *          "post_user"
 *      ),
 * )
 * @Hateoas\Relation(
 *      "get_user",
 *      href = @Hateoas\Route(
 *          "get_user",
 *          parameters = { "id" = "expr(object.getId())" }
 *      )
 * )
 * @Hateoas\Relation(
 *      "put_user",
 *      href = @Hateoas\Route(
 *          "put_user",
 *          parameters = { "id" = "expr(object.getId())" }
 *      )
 * )
 * @Hateoas\Relation(
 *      "delete_user",
 *      href = @Hateoas\Route(
 *          "delete_user",
 *          parameters = { "id" = "expr(object.getId())" }
 *      )
 * )
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true )
     */
    protected $usernameCanonical;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true )
     */
    protected $emailCanonical;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * The salt to use for hashing.
     *
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $salt;

    /**
     * Encrypted password. Must be persisted.
     *
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $password;

    /**
     * User description.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    protected $description = null;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @Assert\NotBlank(groups={"create"})
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $lastLogin;

    /**
     * Random string sent to the user email address in order to verify it.
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $confirmationToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $passwordRequestedAt;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $locked = false;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $expired = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $expiresAt;

    /**
     * @ORM\Column(type="array")
     *
     * @var array
     */
    protected $roles;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $credentialsExpired = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $credentialsExpireAt;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="imageName")
     * 
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @param type $username
     * @param type $email
     * @param type $pass
     */
    public function __construct($username = null, $email = null, $pass = null)
    {
        parent::__construct();
        $this->username = $username;
        $this->email = $email;
        $this->password = $pass;
    }

    /**
     * @param array $user
     *
     * @return \self
     */
    public static function fromArray(array $user = array('username' => null, 'email' => null))
    {
        $rawUser = new self($user['username'], $user['email']);
        $rawUser->setExpired(array_key_exists('expired', $user) ? $user['expired'] : false);
        $rawUser->setLocked(array_key_exists('locked', $user) ? $user['locked'] : false);

        return $rawUser;
    }

    public function addRole($role)
    {
        return parent::addRole($role);
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
        parent::eraseCredentials();
    }

    public function __toString()
    {
        return (string) $this->getUsername();
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
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
     * @return Product
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
     * Get enabled.
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set salt.
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

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
     * Set updatedAt.
     *
     * @param date $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return date $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        if ($password) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the new password is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * Get locked.
     *
     * @return bool
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Get expired.
     *
     * @return bool
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * Get expiresAt.
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Get credentialsExpired.
     *
     * @return bool
     */
    public function getCredentialsExpired()
    {
        return $this->credentialsExpired;
    }

    /**
     * Get credentialsExpireAt.
     *
     * @return \DateTime
     */
    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }
}
