<?php

namespace AppBundle\Model\Grade;

interface GradeSessionInterface
{
    public function getStartDate();
    public function setStartDate($startDate);
    public function getEndDate();
    public function setEndDate($endDate);
    public function getGrade();
    public function setGrade(GradeInterface $grade);
}
