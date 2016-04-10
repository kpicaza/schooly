<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GradeControllerTest extends WebTestCase
{
    const SUBJECT = 'Test Grade';
    const DESCRIPTION = 'ha sido el texto de relleno estándar de las industrias desde el año 1500, ';
    const ROUTE = '/api/grades';
    const ROUTE_ID = '/api/grades/%s';
    const RESOURCE_PICTURE = 'pictures';
    const FILE_PATH = __DIR__ . '/../Resources/';
    const FILE_NAME = 'open-weather.jpg';

    protected $userTest;

    public function setUp()
    {
        parent::setUp();
        $this->userTest = new UserControllerTest();
        $this->userTest->testRegistration();
    }

    public function getLast($client)
    {
        $em = $client->getContainer()->get('doctrine')->getManager();
        $grade = $em->getRepository('AppBundle:Grade\Grade')->findOneBySubject(self::SUBJECT);

        return $grade->getId();
    }

    public function getUploadedFile()
    {
        exec('cp ' . self::FILE_PATH . self::FILE_NAME . ' ' . self::FILE_PATH . self::FILE_NAME . '-1');

        return new UploadedFile(
            self::FILE_PATH . self::FILE_NAME . '-1',
            self::FILE_NAME,
            'image/jpeg',
            123
        );
    }

    public function postPicture($client, $url, $file)
    {
        $client->request(
            'POST',
            $url,
            array(),
            array('imageFile' => $file)
        );

        return $client->getResponse();
    }

    public function testCreate()
    {
        $client = $this->userTest->getClient(true);

        $this->userTest->setRoles($client, array('ROLE_ADMIN'));

        $response = $this->userTest->post(self::ROUTE, array('subject' => self::SUBJECT, 'description' => self::DESCRIPTION), true);

        $this->userTest->unSetRoles(array('ROLE_ADMIN'));

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateWithInvalidParams()
    {
        $client = $this->userTest->getClient(true);

        $this->userTest->setRoles($client, array('ROLE_ADMIN'));

        $response = $this->userTest->post(self::ROUTE, array(), true);

        $this->userTest->unSetRoles(array('ROLE_ADMIN'));

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testValidGetGrades()
    {
        $client = $this->userTest->getClient(true);

        $id = $this->getLast($client);

        $client->request('GET', self::ROUTE);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testNotFoundGetGrade()
    {
        $client = $this->userTest->getClient();

        $client->request('GET', sprintf(self::ROUTE_ID, 'InvalidId'));

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testValidGetGrade()
    {
        $client = $this->userTest->getClient(true);

        $id = $this->getLast($client);

        $client->request('GET', sprintf(self::ROUTE_ID, $id));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPostPictureWithoutAuthorization()
    {
        $client = $this->userTest->getClient(true);

        $id = $this->getLast($client);

        $response = $this->postPicture($client, sprintf(self::ROUTE_ID . '/' . self::RESOURCE_PICTURE, $id), array());

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testPostPictureWithInvalidParams()
    {
        $client = $this->userTest->getClient(true);

        $this->userTest->setRoles($client, array('ROLE_ADMIN'));

        $id = $this->getLast($client);

        $response = $this->userTest->post(sprintf(self::ROUTE_ID, $id) . '/' . self::RESOURCE_PICTURE, array(
            'subject' => self::SUBJECT,
        ), true);

        $this->userTest->unSetRoles(array('ROLE_ADMIN'));

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testPostPicture()
    {
        $client = $this->userTest->getClient(true);

        $this->userTest->setRoles($client, array('ROLE_ADMIN'));

        $id = $this->getLast($client);

        $file = $this->getUploadedFile();

        $response = $this->postPicture($client, sprintf(self::ROUTE_ID . '/' . self::RESOURCE_PICTURE, $id), $file);

        $this->userTest->unSetRoles(array('ROLE_ADMIN'));

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutGradeWithOutAuthentication()
    {
        $client = static::createClient();

        $id = $this->getLast($client);

        $client->request('PUT', sprintf(self::ROUTE_ID, $id));

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testPutGradeWithOutRequiredParams()
    {
        $client = $this->userTest->getClient(true);
        $id = $this->getLast($client);
        $this->userTest->setRoles($client, array('ROLE_ADMIN'));

        $response = $this->userTest->put(sprintf(self::ROUTE_ID, $id), array(
            'subject' => null,
        ), true);

        $this->userTest->unSetRoles(array('ROLE_ADMIN'));

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testPutGradeWithValidParams()
    {
        $client = $this->userTest->getClient(true);
        $id = $this->getLast($client);
        $this->userTest->setRoles($client, array(
            'ROLE_ADMIN'
        ));


        $response = $this->userTest->put(sprintf(self::ROUTE_ID, $id), array(
            'subject' => self::SUBJECT,
        ), true);

        $this->userTest->unSetRoles(array('ROLE_ADMIN'));

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteWithoutPermissions()
    {
        $client = $this->userTest->getClient(true);

        $id = $this->getLast($client);

        $client->request('DELETE', sprintf(self::ROUTE_ID, $id));

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $client = $this->userTest->getClient(true);

        $this->userTest->setRoles($client, array(
            'ROLE_ADMIN'
        ));

        $id = $this->getLast($client);

        $client->request('DELETE', sprintf(self::ROUTE_ID, $id));

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->userTest->testDeleteUser();
    }

}