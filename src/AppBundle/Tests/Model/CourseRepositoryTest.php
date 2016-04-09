<?php

namespace AppBundle\Tests\Model;

use AppBundle\Entity\Course\Course;
use AppBundle\Entity\Course\CourseGateway;
use AppBundle\Model\Course\CourseFactory;
use AppBundle\Model\Course\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\File;

class CourseRepositoryTest extends WebTestCase
{

    const NAME = 'Test course';
    const DESCRIPTION = 'ha sido el texto de relleno estándar de las industrias desde el año 1500, ';
    const ENABLED = true;
    const IMAGE_FILE = __DIR__ . '/../Resources/open-weather.jpg';
    const IMAGE_NAME = 'open-weather.jpg';

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
        $gatewayClassname = 'AppBundle\Entity\Course\CourseGateway';
        $this->gateway = $this->prophesize($gatewayClassname);
        $this->factory = new CourseFactory();
        $this->repository = new CourseRepository($this->gateway->reveal(), $this->factory);
    }

    public function testCourse()
    {
        $course = new Course();
        $course
            ->setName(self::NAME)
            ->setDescription(self::DESCRIPTION)
            ->setEnabled(self::ENABLED)
            ->setUpdatedAt(new \DateTime())
        ;
    }

    public function testFindOneByWithParams()
    {
        $fakeCourse = new Course();
        $fakeCourse = $fakeCourse->setName(self::NAME);

        $this->gateway->findOneBy(array('name' => self::NAME))->willReturn($fakeCourse);
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
            $this->assertEquals($course->getDescription(), $fakeCourses[$key]->getDescription());
            $this->assertEquals($course->getEnabled(), $fakeCourses[$key]->getEnabled());
            $this->assertEquals($course->isEnabled(), $fakeCourses[$key]->isEnabled());
            $this->assertEquals($course->getUpdatedAt(), $fakeCourses[$key]->getUpdatedAt());
        }
    }

    public function testAddFileWithParams()
    {
        $fakeCourse = new Course();
        $fakeCourse = $fakeCourse->setName(self::NAME);

        $file = new File(self::IMAGE_FILE);

        $course = $this->repository->addFile($fakeCourse, $file, self::IMAGE_NAME);
        $this->assertTrue($course instanceof Course);
        $this->assertEquals($course->getImageFile(), self::IMAGE_FILE);
        $this->assertEquals($course->getImageName(), self::IMAGE_NAME);
    }

}
