<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Tests\Controller\UserControllerTest;

class CourseControllerTest extends WebTestCase
{

    const NAME = 'new course';
    const ROUTE = '/api/courses';
    const ROUTE_ID = '/api/courses/%s';

    protected $userTest;

    public function setUp()
    {
        parent::setUp();
        $this->userTest = new UserControllerTest();
        $this->userTest->testRegistration();
    }

    public function getLast($client)
    {
        $em = $client->getContainer()->get('doctrine_mongodb')->getManager();
        $course = $em->getRepository('AppBundle:Course\Course')->findOneByName(self::NAME);
        
        return $course->getId();
    }
    
    public function testCreateCourse()
    {
        $response = $this->userTest->post(self::ROUTE, array(
          'name' => self::NAME,
        ), true);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testInvalidFormParamsCreateCourse()
    {
        $response = $this->userTest->post(self::ROUTE, array(), true);
        
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testValidGetCourse()
    {
        $client = $this->userTest->getClient(true);
        
        $id = $this->getLast($client);
        
        $client->request('GET', sprintf(self::ROUTE_ID, $id));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testNotFoundGetCourse()
    {
        $client = $this->userTest->getClient(true);
                
        $client->request('GET', sprintf(self::ROUTE_ID, 'InvalidId'));

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testValidGetCourses()
    {
        $client = $this->userTest->getClient();

        $client->request('GET', self::ROUTE);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPutCourseWithOutAuthentication()
    {
        $client = static::createClient();

        $id = $this->getLast($client);

        $client->request('PUT', sprintf(self::ROUTE_ID, $id));

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testPutCourseWithOutRequiredParams()
    {
        $client = $this->userTest->getClient(true);
        $id = $this->getLast($client);
        $this->userTest->setRoles($client, array(
          'ROLE_ADMIN'
        ));
        

        $response = $this->userTest->put(sprintf(self::ROUTE_ID, $id), array(
          'name' => null,
            ), true);

        $this->userTest->unSetRoles(array('ROLE_ADMIN'));

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testDeleteCourseWithoutPermissions()
    {
        $client = $this->userTest->getClient(true);

        $id = $this->getLast($client);

        $client->request('DELETE', sprintf(self::ROUTE_ID, $id));

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
    
    public function testDeleteCourse()
    {
        $client = $this->userTest->getClient(true);

        $this->userTest->setRoles($client, array(
          'ROLE_ADMIN'
        ));
        
        $id = $this->getLast($client);

        $client->request('DELETE', sprintf(self::ROUTE_ID, $id));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->userTest->testDeleteUser();
    }

}
