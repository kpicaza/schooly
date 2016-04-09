<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GradeControllerTest extends WebTestCase
{
    const SUBJECT = 'Test Grade';
    const DESCRIPTION = 'ha sido el texto de relleno estándar de las industrias desde el año 1500, ';
    const ROUTE = '/api/grades';

    protected $userTest;

    public function setUp()
    {
        parent::setUp();
        $this->userTest = new UserControllerTest();
        $this->userTest->testRegistration();
    }

    public function testCreateGrade()
    {
        $client = $this->userTest->getClient(true);

        $this->userTest->setRoles($client, array('ROLE_ADMIN'));

        $response = $this->userTest->post(self::ROUTE, array('subject' => self::SUBJECT, 'description' => self::DESCRIPTION), true);

        $this->userTest->unSetRoles(array('ROLE_ADMIN'));

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->userTest->testDeleteUser();
    }

}