<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MeControllerTest extends WebTestCase
{

    const NAME = 'meco';
    const PASS = 'Demo1234';
    const ROUTE = '/api/me.json';

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

    public function testValidGetMe()
    {
        $client = $this->createAuthenticatedClient(self::NAME, self::PASS);

        $client->request('GET', self::ROUTE);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
