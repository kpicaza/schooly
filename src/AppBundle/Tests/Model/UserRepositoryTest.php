<?php

namespace AppBundle\Tests\Model;

use AppBundle\Entity\User\User;
use AppBundle\Entity\User\UserGateway;
use AppBundle\Model\User\UserFactory;
use AppBundle\Model\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase
{
    const USER = 'koldo';
    const EMAIL = 'koldo@koldo.mail';
    const PASS = 'Demo1234';
    const DESC = 'Hola mondo';

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
        $gatewayClassname = 'AppBundle\Entity\User\UserGateway';
        $this->gateway = $this->prophesize($gatewayClassname);
        $this->factory = new UserFactory();
        $this->repository = new UserRepository($this->gateway->reveal(), $this->factory);
    }

    public function testUser()
    {
        $user = new User();
    }
    
    public function testFindOneByWithParams()
    {
        $fakeUser = new User();
        $fakeUser = $fakeUser->fromArray(array('username' => self::USER, 'email' => self::EMAIL, 'password' => self::PASS, 'description' => self::DESC));

        $this->gateway->findOneBy(array('username' => self::USER), array())->willReturn($fakeUser);
        $fakeUser = $this->factory->makeOne($fakeUser);

        $user = $this->repository->findOneBy(array('username' => self::USER));

        $this->assertTrue($user instanceof User);
        $this->assertEquals($user->getUsername(), $fakeUser->getUsername());
        $this->assertEquals($user->getEmail(), $fakeUser->getEmail());
        $this->assertEquals($user->getDescription(), $fakeUser->getDescription());
        $this->assertEquals($user->getUsername(), $user->__toString());
        $this->assertEquals($user->getEnabled(), $fakeUser->getEnabled());
        $this->assertEquals($user->getLocked(), $fakeUser->getLocked());
        $this->assertEquals($user->getExpired(), $fakeUser->getExpired());
        $this->assertEquals($user->getExpiresAt(), $fakeUser->getExpiresAt());
        $this->assertEquals($user->getCredentialsExpired(), $fakeUser->getCredentialsExpired());
        $this->assertEquals($user->getCredentialsExpireAt(), $fakeUser->getCredentialsExpireAt());
    }
}
