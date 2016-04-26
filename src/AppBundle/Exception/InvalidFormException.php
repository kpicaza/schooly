<?php

namespace AppBundle\Exception;

class InvalidFormException extends \RuntimeException
{
    protected $form;
    public function __construct($message, $form = null, $code = 400)
    {
        parent::__construct($message, $code);
        $this->form = $form;
    }
    /**
     * @return array|null
     */
    public function getForm()
    {
        return $this->form;
    }
}
