<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MeControllerTest extends WebTestCase
{
    const NAME = 'meco';
    const MAIL = 'meco@mail.com';
    const PASS = 'Demo1234';
    const DESCRIPTION = 'ha sido el texto de relleno estándar de las industrias desde el año 1500, '
        .'cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido';
    const ROUTE = '/api/me.json';
    const REGISTER_ROUTE = '/api/register/me.json';

    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
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

    protected function getClient($auth = false)
    {
        if (true === $auth) {
            $client = $this->createAuthenticatedClient(self::NAME, self::PASS);
        } else {
            $client = static::createClient();
        }

        return $client;
    }

    public function testRegistrationFailedWithEmptyForm()
    {
        $client = static::createClient();

        $client->request('POST', self::REGISTER_ROUTE);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testRegistrationFailedWithOutAllParams()
    {
        $response = $this->post(self::REGISTER_ROUTE, array(
          'username' => self::NAME,
          'email' => self::MAIL,
          'plainPassword' => null,
          'password' => self::PASS,
            ), true);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testRegistration()
    {
        $response = $this->post(self::REGISTER_ROUTE, array(
          'username' => self::NAME,
          'email' => self::MAIL,
          'plainPassword' => self::PASS,
          'password' => self::PASS,
            ), true);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRegistrationTwiceMustFail()
    {
        $response = $this->post(self::REGISTER_ROUTE, array(
          'username' => self::NAME,
          'email' => self::MAIL,
          'plainPassword' => self::PASS,
          'password' => self::PASS,
            ), true);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testPutMeWithOutAuthentication()
    {
        $client = static::createClient();

        $client->request('PUT', self::ROUTE);

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testPutMeWithOutRequiredParams()
    {
        $response = $this->put(self::ROUTE, array(
          'email' => null,
          'description' => self::DESCRIPTION,
            ), true);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testPutMe()
    {
        $response = $this->put(self::ROUTE, array(
          'email' => 'asd'.self::MAIL,
          'description' => self::DESCRIPTION,
            ), true);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutMeDeleteDescription()
    {
        $response = $this->put(self::ROUTE, array(
          'email' => 'asd'.self::MAIL,
          'description' => '',
            ), true);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUnauthorizedGetMe()
    {
        $client = static::createClient();

        $client->request('GET', self::ROUTE);

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testInvalidCredentialsGetMe()
    {
        $client = $this->createAuthenticatedClient('bad', 'pass');

        $client->request('GET', self::ROUTE);

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testValidGetMe()
    {
        $client = $this->createAuthenticatedClient(self::NAME, self::PASS);

        $client->request('GET', self::ROUTE);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteMe()
    {
        $client = $this->createAuthenticatedClient(self::NAME, self::PASS);

        $client->request('DELETE', self::ROUTE);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
