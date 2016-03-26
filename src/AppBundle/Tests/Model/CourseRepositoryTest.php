<?php

namespace AppBundle\Tests\Model;

use AppBundle\Document\Course\Course;
use AppBundle\Document\Course\CourseGateway;
use AppBundle\Model\Course\CourseFactory;
use AppBundle\Model\Course\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CourseRepositoryTest extends WebTestCase
{

    const NAME = 'Test course';

    /**
     * @var UserGateway
     */
    private $gateway;

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * Set up UserRepository.
     */
    public function setUp()
    {
        parent::setUp();
        $gatewayClassname = 'AppBundle\Document\Course\CourseGateway';
        $this->gateway = $this->prophesize($gatewayClassname);
        $this->factory = new CourseFactory();
        $this->repository = new CourseRepository($this->gateway->reveal(), $this->factory);
    }

    public function testCourse()
    {
        $course = new Course();
    }

    public function testFindOneByWithParams()
    {
        $fakeCourse = new Course();
        $fakeCourse = $fakeCourse->setName(self::NAME);

        $this->gateway->findOneBy(array('name' => self::NAME), array())->willReturn($fakeCourse);
        $fakeCourse = $this->factory->makeOne($fakeCourse);

        $course = $this->repository->findOneBy(array('name' => self::NAME));

        $this->assertTrue($course instanceof Course);
        $this->assertEquals($course->getName(), $fakeCourse->getName());
    }

    public function testFindByWithParams()
    {
        $fakeCourse = new Course();
        $fakeCourse = $fakeCourse->setName(self::NAME);

        $this->gateway->findBy(array('name' => self::NAME), null, null, null)->willReturn(array($fakeCourse));
        $fakeCourses = $this->factory->makeAll(array($fakeCourse));

        $courses = $this->repository->findBy(array('name' => self::NAME), null, null, null);
        foreach ($courses as $key => $course) {

            $this->assertTrue($course instanceof Course);
            $this->assertEquals($course->getName(), $fakeCourses[$key]->getName());
        }
    }

}
