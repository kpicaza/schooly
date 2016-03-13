<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    const NAME = 'meco';
    const MAIL = 'meco@mail.com';
    const PASS = 'Demo1234';
    const DESCRIPTION = 'ha sido el texto de relleno estándar de las industrias desde el año 1500, '
        . 'cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido';
    const ROUTE = '/api/users/%s';
    const REGISTER_ROUTE = '/api/users';

    /**
     * Create a client with a default Authorization header. 
     *
     * @param string $username
     * @param string $password
     * @see https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/3-functional-testing.md
     * 
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($username = 'user', $password = 'password')
    {
        $client = static::createClient();
        $client->request(
            'POST', '/api/login_check', array(
            '_username' => $username,
            '_password' => $password,
            )
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        if (array_key_exists('token', $data)) {
            $client = static::createClient();
            $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
        }

        return $client;
    }
    
    protected function getLast($client)
    {
        $em = $client->getContainer()->get('doctrine_mongodb')->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneByUsername(self::NAME);
        
        return $user->getId();
    }

    protected function getClient($auth = false)
    {
        if (true === $auth) {
            $client = $this->createAuthenticatedClient(self::NAME, self::PASS);
        }
        else {
            $client = static::createClient();
        }
        return $client;
    }

    protected function post($uri, array $data, $auth = false)
    {
        $client = $this->getClient($auth);
        
        $client->request('POST', $uri, $data);
        
        return $client->getResponse();
    }

    protected function put($uri, array $data, $auth = false)
    {
        $client = $this->getClient($auth);

        $client->request('PUT', $uri, $data);

        return $client->getResponse();
    }
    
    public function testRegistrationFailedWithEmptyForm()
    {
        $client = static::createClient();
        
        $client->request('POST', self::REGISTER_ROUTE);
        
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testRegistration()
    {
        $response = $this->post(self::REGISTER_ROUTE, array(
            'username' => self::NAME,
            'email' => self::MAIL,
            'plainPassword' => self::PASS,
            'password' => self::PASS,
            ));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testValidGetUser()
    {
        $client = $this->createAuthenticatedClient(self::NAME, self::PASS);

        $id = $this->getLast($client);

        $client->request('GET', sprintf(self::ROUTE, $id));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testPutUserWithOutAuthentication()
    {
        $client = static::createClient();

        $id = $this->getLast($client);
        
        $client->request('PUT', sprintf(self::ROUTE, $id));

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testPutUserWithOutRequiredParams()
    {
        $client = static::createClient();
        $id = $this->getLast($client);

        $response = $this->put(sprintf(self::ROUTE, $id), array(
          'email' => null,
          'description' => self::DESCRIPTION,
            ), true);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testPutUser()
    {
        $client = static::createClient();
        $id = $this->getLast($client);

        $response = $this->put(sprintf(self::ROUTE, $id), array(
          'email' => 'asd' . self::MAIL,
          'description' => self::DESCRIPTION,
            ), true);

        $this->assertEquals(200, $response->getStatusCode());
    }
    
    public function testDeleteUser()
    {
        $client = $this->createAuthenticatedClient(self::NAME, self::PASS);
        
        $id = $this->getLast($client);

        $client->request('DELETE', sprintf(self::ROUTE, $id));
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
