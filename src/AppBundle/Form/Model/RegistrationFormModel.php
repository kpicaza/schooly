<?php
namespace AppBundle\Form\Model;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * RegistrationFormModel.
 */
class RegistrationFormModel
{
    const NAME = 'username';
    const MAIL = 'email';
    const PLAIN = 'plainPassword';
    const PASS = 'password';
    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/[a-zA-Z0-9]/")
     *
     * @var string
     */
    protected $username;
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @var string
     */
    protected $email;
    /**
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $plainPassword;
    /**
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $password;
    public function __construct($username = null, $email = null, $plainPassword = null, $password = null)
    {
        $this->username = $username;
        $this->email = $email;
        $this->plainPassword = $plainPassword;
        $this->password = $password;
    }
    /**
     * @param array $user
     *
     * @return \self
     */
    public static function fromArray(array $user = array(self::NAME => null, self::MAIL => null, self::PLAIN => null, self::PASS => null))
    {
        return new self(
            array_key_exists(self::NAME, $user) ? $user[self::NAME] : null,
            array_key_exists(self::MAIL, $user) ? $user[self::MAIL] : null,
            array_key_exists(self::PLAIN, $user) ? $user[self::PLAIN] : null,
            array_key_exists(self::PASS, $user) ? $user[self::PASS] : null
        );
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function getPassword()
    {
        return $this->password;
    }
}