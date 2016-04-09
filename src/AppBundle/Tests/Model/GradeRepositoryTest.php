<?php

namespace AppBundle\Tests\Model;

use AppBundle\Entity\Grade\Grade;
use AppBundle\Entity\Grade\GradeGateway;
use AppBundle\Model\Grade\GradeFactory;
use AppBundle\Model\Grade\GradeRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\File;


class GradeRepositoryTest extends WebTestCase
{

    const SUBJECT = 'Test Grade subject';
    const DESCRIPTION = 'ha sido el texto de relleno estándar de las industrias desde el año 1500, ';

    /**
     * @var GradeGateway
     */
    private $gateway;

    /**
     * @var GradeRepository
     */
    private $repository;

    /**
     * Set up GradeRepository.
     */
    public function setUp()
    {
        parent::setUp();
        $gatewayClassname = 'AppBundle\Entity\Grade\GradeGateway';
        $this->gateway = $this->prophesize($gatewayClassname);
        $this->factory = new GradeFactory();
        $this->repository = new GradeRepository($this->gateway->reveal(), $this->factory);
    }

    public function testGrade()
    {
        $grade = new Grade();

        $grade
            ->setSubject(self::SUBJECT)
            ->setDescription(self::DESCRIPTION)
        ;
    }

}