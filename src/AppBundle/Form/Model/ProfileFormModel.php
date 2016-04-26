<?php

namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserFormModel.
 */
class ProfileFormModel implements UserFormModelInterface
{
    const MAIL = 'email';
    const DESC = 'description';

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @var string
     */
    protected $email;

    /**
     * @Assert\Regex("/[a-z\d\-_\s]+/")
     *
     * @var string
     */
    protected $description;
    //protected $picture;
    /**
     * @param type $username
     * @param type $email
     * @param type $description
     */
    public function __construct($username = null, $email = null, $description = null)
    {
        $this->username = $username;
        $this->email = $email;
        $this->description = $description;
    }

    /**
     * @param array $user
     *
     * @return \self
     */
    public static function fromArray(array $user = array(self::MAIL => null, self::DESC => null))
    {
        return new self(
            array_key_exists(self::MAIL, $user) ? $user[self::MAIL] : null,
            array_key_exists(self::DESC, $user) ? $user[self::DESC] : null
        );
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }
    /*
    public function setPicture(File $file = null)
    {
        $this->picture = $file;
    }

    public function getPicture()
    {
        return $this->picture;
    }
    */
}
