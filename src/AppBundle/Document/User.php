<?php

// src/AppBundle/Document/User.php

namespace AppBundle\Document;

use AppBundle\Model\UserInterface;
use FOS\UserBundle\Document\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\UniqueIndex;

/**
 * User.
 * 
 * @MongoDB\Document(repositoryClass="AppBundle\Document\UserGateway")
 */
class User extends BaseUser implements UserInterface
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
    protected $username;

    /**
     * @var string
     * 
     * @MongoDB\String
     * @UniqueIndex
     */
    protected $usernameCanonical;

    /**
     * @var string
     * 
     * @MongoDB\String
     */
    protected $email;

    /**
     * @var string
     * 
     * @MongoDB\String
     * @UniqueIndex
     */
    protected $emailCanonical;

    /**
     * @var bool
     * 
     * @MongoDB\Bool
     */
    protected $enabled;

    /**
     * The salt to use for hashing.
     *
     * @var string
     * 
     * @MongoDB\String
     */
    protected $salt;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     * 
     * @MongoDB\String
     */
    protected $password;

    /**
     * User description.
     *
     * @var string
     *
     * @MongoDB\String(nullable=true)
     */
    protected $description = null;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * @var \DateTime
     * 
     * @MongoDB\Date(nullable=true)
     */
    protected $lastLogin;

    /**
     * Random string sent to the user email address in order to verify it.
     *
     * @var string
     *
     * @MongoDB\String(nullable=true)
     */
    protected $confirmationToken;

    /**
     * @var \DateTime
     * 
     * @MongoDB\Date(nullable=true)
     */
    protected $passwordRequestedAt;

    /**
     * @var bool
     * 
     * @MongoDB\Bool
     */
    protected $locked = false;

    /**
     * @var bool
     * 
     * @MongoDB\Bool
     */
    protected $expired = false;

    /**
     * @var \DateTime
     * 
     * @MongoDB\Date(nullable=true)
     */
    protected $expiresAt;

    /**
     * @var array
     * 
     * @MongoDB\Collection
     */
    protected $roles;

    /**
     * @var bool
     * 
     * @MongoDB\Bool
     */
    protected $credentialsExpired = false;

    /**
     * @var \DateTime
     * 
     * @MongoDB\Date(nullable=true)
     */
    protected $credentialsExpireAt;

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
     * Get enabled
     *
     * @return bool $enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * Get locked
     *
     * @return bool $locked
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Get expired
     *
     * @return bool $expired
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * Get expiresAt
     *
     * @return date $expiresAt
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Get credentialsExpired
     *
     * @return bool $credentialsExpired
     */
    public function getCredentialsExpired()
    {
        return $this->credentialsExpired;
    }

    /**
     * Get credentialsExpireAt
     *
     * @return date $credentialsExpireAt
     */
    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }
}
