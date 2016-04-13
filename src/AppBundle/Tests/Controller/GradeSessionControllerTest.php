<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GradeSessionControllerTest extends WebTestCase
{
    const SUBJECT = 'Test GradeSession';
    const DESCRIPTION = 'ha sido el texto de relleno estándar de las industrias desde el año 1500, ';
    const ROUTE = '/api/grades/%s/sessions';
    const ROUTE_ID = '/api/grade/%s/sessions/%s';
    const FORMAT = 'Y-m-d H:i:s';

    protected $userTest;

    protected $em;

    public function setUp()
    {
        parent::setUp();
        $this->userTest = new UserControllerTest();
        $this->userTest->testRegistration();
    }

    public function getDoctrine($client)
    {
        $this->em = $client->getContainer()->get('doctrine')->getManager();
    }

    public function getLast($courseId)
    {
        $grade = $this->em->getRepository('AppBundle:Grade\GradeSession')->findOneByCourse($courseId);

        return $grade->getId();
    }

    public function testCreateWithoutRequiredParams()
    {
        $client = $this->userTest->getClient(true);
        $this->userTest->setRoles($client, array('ROLE_ADMIN'));

        $response = $this->userTest->post(
            sprintf(self::ROUTE, 1),
            array(
                'start_date' => null,
                'end_date' => 'sdasad lkjasd ljasld lk s'
            ),
            true
        );

        $this->userTest->unSetRoles(array('ROLE_ADMIN'));

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCreate()
    {
        $client = $this->userTest->getClient(true);
        $this->userTest->setRoles($client, array('ROLE_ADMIN'));

        $date = new \DateTime();

        $response = $this->userTest->post(
            sprintf(self::ROUTE, 1),
            array(
                'start_date' => $date->format(self::FORMAT),
                'end_date' => $date->modify('2 MONTH')->format(self::FORMAT),
            ),
            true
        );

        $this->userTest->unSetRoles(array('ROLE_ADMIN'));

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testValidGetGradeSessions()
    {
        $client = $this->userTest->getClient(true);

        $client->request('GET', sprintf(self::ROUTE, 1));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->userTest->testDeleteUser();
    }

}