<?php

namespace AppBundle\Tests\Model;

use AppBundle\Entity\Grade\GradeSession;
use AppBundle\Entity\Grade\GradeSessionGateway;
use AppBundle\Entity\Grade\Grade;
use AppBundle\Model\Grade\GradeSessionFactory;
use AppBundle\Model\Grade\GradeSessionRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\File;

class GradeSessionRepositoryTest extends WebTestCase
{

    const NAME = 'Test GradeSession';
    const DESCRIPTION = 'ha sido el texto de relleno estándar de las industrias desde el año 1500, ';
    const ENABLED = true;

    /**
     * @var GradeSessionGateway
     */
    private $gateway;

    /**
     * @var GradeSessionRepository
     */
    private $repository;

    /**
     * Set up GradeSessionRepository.
     */
    public function setUp()
    {
        parent::setUp();
        $gatewayClassname = 'AppBundle\Entity\Grade\GradeSessionGateway';
        $this->gateway = $this->prophesize($gatewayClassname);
        $this->factory = new GradeSessionFactory();
        $this->repository = new GradeSessionRepository($this->gateway->reveal(), $this->factory);
    }

    public function testGradeSession()
    {
        $gradeSession = new GradeSession();
        $gradeSession
            ->setEndDate(new \DateTime())
            ->setStartDate(new \DateTime())
            ->setGrade(new Grade())
        ;
    }
}