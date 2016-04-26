<?php

namespace AppBundle\Model\Course;

interface CourseInterface
{
    public function getId();
    public function getName();
    public function setName($name);
    public function getDescription();
    public function setDescription($description);
}
