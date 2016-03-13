<?php

namespace AppBundle\Form\Model;

interface UserFormModelInterface
{
    /**
     * @param array $user
     *
     * @return \self
     */
    public static function fromArray(array $user = array());

    /**
     * @param string $email
     */
    public function setEmail($email);

    public function getEmail();
}
