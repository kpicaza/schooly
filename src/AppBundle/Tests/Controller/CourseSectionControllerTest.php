<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CourseSectionControllerTest extends WebTestCase
{
    const NAME = 'new course';
    const ROUTE = '/api/courses';
    const ROUTE_ID = '/api/courses/%s';
    const RESOURCE_PICTURE = 'pictures';
    const DESCRIPTION = 'ha sido el texto de relleno estándar de las industrias desde el año 1500, '
    .'cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido';
    const FILE_PATH = __DIR__.'/../Resources/';
    const FILE_NAME = 'open-weather.jpg';

    protected $userTest;

    public function setUp()
    {
        parent::setUp();
        $this->userTest = new UserControllerTest();
        $this->userTest->testRegistration();
    }

    public function testPassTest()
    {
        $this->assertTrue(true);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->userTest->testDeleteUser();
    }
}
