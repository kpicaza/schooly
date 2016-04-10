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
    const ENABLED = true;

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
            ->setEnabled(self::ENABLED)
            ->setUpdatedAt(new \DateTime())
        ;
    }

    public function testFindOneByWithParams()
    {
        $fakeGrade = new Grade();
        $fakeGrade = $fakeGrade->setSubject(self::SUBJECT);

        $this->gateway->findOneBy(array('subject' => self::SUBJECT))->willReturn($fakeGrade);
        $fakeGrade= $this->factory->makeOne($fakeGrade);

        $grade = $this->repository->findOneBy(array('subject' => self::SUBJECT));

        $this->assertTrue($grade instanceof Grade);
        $this->assertEquals($grade->getSubject(), $fakeGrade->getSubject());
    }

    public function testFindByWithParams()
    {
        $fakeGrade = new Grade();
        $fakeGrade = $fakeGrade->setSubject(self::SUBJECT);

        $this->gateway->findBy(array('subject' => self::SUBJECT), null, null, null)->willReturn(array($fakeGrade));
        $fakeGrades = $this->factory->makeAll(array($fakeGrade));

        $grades = $this->repository->findBy(array('subject' => self::SUBJECT), null, null, null);
        foreach ($grades as $key => $grade) {

            $this->assertTrue($grade instanceof Grade);
            $this->assertEquals($grade->getDescription(), $fakeGrades[$key]->getDescription());
            $this->assertEquals($grade->getEnabled(), $fakeGrades[$key]->getEnabled());
            $this->assertEquals($grade->isEnabled(), $fakeGrades[$key]->isEnabled());
            $this->assertEquals($grade->getUpdatedAt(), $fakeGrades[$key]->getUpdatedAt());
        }
    }

}