<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GradeSessionControllerTest extends WebTestCase
{
    const SUBJECT = 'Test GradeSession';
    const DESCRIPTION = 'ha sido el texto de relleno estándar de las industrias desde el año 1500, ';
    const ROUTE = '/api/grades/%s/sessions';
    const ROUTE_ID = '/api/grades/%s/sessions/%s';
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

    public function getLastGrade()
    {
        $grade = $this->em->getRepository('AppBundle:Grade\Grade')->findOneBy(array());

        return $grade->getId();
    }

    public function getLast($gradeId)
    {
        $grade = $this->em->getRepository('AppBundle:Grade\GradeSession')->findOneByGrade($gradeId);

        return $grade->getId();
    }

    public function testOptionsMethod()
    {
        $client = $this->userTest->getClient(true);

        $this->getDoctrine($client);

        $client->request('OPTIONS', sprintf(self::ROUTE, $this->getLastGrade()));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateWithoutRequiredParams()
    {
        $client = $this->userTest->getClient(true);
        $this->getDoctrine($client);
        $this->userTest->setRoles($client, array('ROLE_TEACHER'));

        $response = $this->userTest->post(
            sprintf(self::ROUTE, $this->getLastGrade()),
            array(
                'start_date' => null,
                'end_date' => 'sdasad lkjasd ljasld lk s',
            ),
            true
        );

        $this->userTest->unSetRoles(array('ROLE_TEACHER'));

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCreate()
    {
        $client = $this->userTest->getClient(true);
        $this->getDoctrine($client);
        $this->userTest->setRoles($client, array('ROLE_TEACHER'));

        $date = new \DateTime();

        $response = $this->userTest->post(
            sprintf(self::ROUTE, $this->getLastGrade()),
            array(
                'start_date' => $date->format(self::FORMAT),
                'end_date' => $date->modify('2 MONTH')->format(self::FORMAT),
            ),
            true
        );

        $this->userTest->unSetRoles(array('ROLE_TEACHER'));

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testValidGetGradeSessions()
    {
        $client = $this->userTest->getClient(true);
        $this->getDoctrine($client);

        $client->request('GET', sprintf(self::ROUTE, $this->getLastGrade()));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testNotFoundGetGradeSessions()
    {
        $client = $this->userTest->getClient(true);
        $this->getDoctrine($client);

        $client->request('GET', sprintf(self::ROUTE, 'abc'));

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testNotFoundGetGradeSession()
    {
        $client = $this->userTest->getClient(true);

        $this->getDoctrine($client);

        $grade_id = $this->getLastGrade();

        $client->request('GET', sprintf(self::ROUTE_ID, $grade_id, 'fasdfsf'));

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testValidGetGradeSession()
    {
        $client = $this->userTest->getClient(true);

        $this->getDoctrine($client);

        $grade_id = $this->getLastGrade();

        $client->request('GET', sprintf(self::ROUTE_ID, $grade_id, $this->getLast($grade_id)));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPutWithOutValidCredentials()
    {
        $client = $this->userTest->getClient(true);
        $this->getDoctrine($client);

        $date = new \DateTime();

        $grade_id = $this->getLastGrade();

        $response = $this->userTest->put(
            sprintf(self::ROUTE_ID, $grade_id, $this->getLast($grade_id)),
            array(
                'start_date' => $date->format(self::FORMAT),
                'end_date' => $date->modify('5 MONTH')->format(self::FORMAT),
            )
        );

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testPutWithOutValidPermissions()
    {
        $client = $this->userTest->getClient(true);
        $this->getDoctrine($client);

        $date = new \DateTime();

        $grade_id = $this->getLastGrade();

        $response = $this->userTest->put(
            sprintf(self::ROUTE_ID, $grade_id, $this->getLast($grade_id)),
            array(
                'start_date' => $date->format(self::FORMAT),
                'end_date' => $date->modify('5 MONTH')->format(self::FORMAT),
            ),
            true
        );

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testPutWithValidParams()
    {
        $client = $this->userTest->getClient(true);
        $this->getDoctrine($client);
        $this->userTest->setRoles($client, array('ROLE_TEACHER'));

        $date = new \DateTime();

        $grade_id = $this->getLastGrade();

        $response = $this->userTest->put(
            sprintf(self::ROUTE_ID, $grade_id, $this->getLast($grade_id)),
            array(
                'start_date' => $date->format(self::FORMAT),
                'end_date' => $date->modify('5 MONTH')->format(self::FORMAT),
            ),
            true
        );

        $this->userTest->unSetRoles(array('ROLE_TEACHER'));

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testPutWithInvalidParams()
    {
        $client = $this->userTest->getClient(true);
        $this->getDoctrine($client);
        $this->userTest->setRoles($client, array('ROLE_TEACHER'));

        $date = new \DateTime();

        $grade_id = $this->getLastGrade();

        $response = $this->userTest->put(
            sprintf(self::ROUTE_ID, $grade_id, $this->getLast($grade_id)),
            array(
                'start_date' => 'fdsfdkhjds',
                'end_date' => 'dsdasdasdas',
            ),
            true
        );

        $this->userTest->unSetRoles(array('ROLE_TEACHER'));

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testDelete()
    {
        $client = $this->userTest->getClient(true);
        $this->getDoctrine($client);

        $this->userTest->setRoles($client, array(
            'ROLE_ADMIN',
        ));

        $grade_id = $this->getLastGrade();

        $client->request('DELETE', sprintf(self::ROUTE_ID, $grade_id, $this->getLast($grade_id)));

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->userTest->testDeleteUser();
    }
}
