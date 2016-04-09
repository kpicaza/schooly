<?php

namespace AppBundle\Model\Grade;

interface GradeInterface
{
    public function getId();
    public function getSubject();
    public function setSubject($subject);
    public function getDescription();
    public function setDescription($description);
}