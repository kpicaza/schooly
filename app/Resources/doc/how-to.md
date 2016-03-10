 Práctica Symfony - Rest API, Oauth2, JWT, repository pattern.
===============================================================

![Irontec](https://www.google.es/url?sa=i&rct=j&q=&esrc=s&source=images&cd=&cad=rja&uact=8&ved=0ahUKEwis-qbP8LXLAhUI6RQKHXOODdAQjRwIBw&url=http%3A%2F%2Fwww.eoi.es%2Fblogs%2F20abierta%2Firontec-software-de-codigo-abierto-y-negocios%2F&psig=AFQjCNHdot6F0mMY1uPBOx5OH9m6VtxHGA&ust=1457691084051890)


## Aplicación de práctica:

Lo importante en este caso no es desarrollar una lógica extraordinaria, el objetivo reside en comprender las diferentes secciones que hemos analizado 
durante la parte teórica.

La aplicación consiste en un sistema de registro/login con autenticación JWT via Rest, siguiendo el patrón repositorio, aplicando el principio de 
inversión de dependencias y programando dirigidos por tests.

Primero definiremos los user storys.

### User stories

1. Como usuario sin autenticar puedo puedo acceder al login.
1. Como usuario sin autenticar puedo registrarme en el site.
1. Como usuario autenticado puedo editar mi perfil.
1. Como usuario autenticado puedo obtener la información de mi perfil.
1. Como usuario autenticado puedo darme de baja.

Al final tendremos un sistema de usuarios independiente del motor de base de datos, en nuestro caso implementaremos el servicio rest, que podría alimentar 
cuarquier aplicación creada con frameworks de frontend como angular o ember

Una vez definidos los `user stories`, pasamos a diferenciar la distintas parte de la aplicación en su conjunto:

### Entidades

* User
    * FOSUserBundle

### ROLES

* ROLE_USER
* ROLE_ADMIN
* ROLE_SUPER_ADMIN

### Rest API:

#### API:
* **FOSRest** 
    * FOSRestBundle

                "jms/serializer-bundle": "^1.1",
                "friendsofsymfony/rest-bundle": "^1.7",

* **CORS**
    * NelmioCorsBundle

                "nelmio/cors-bundle": "^1.4",


#### Usuarios

* **FOSUser:**
    * FOSOUserBundle

                "friendsofsymfony/user-bundle": "^1.3",


#### Autenticación:

* **OAuth2:**
    * FOSOAuthServerBundle

                "friendsofsymfony/rest-bundle": "^1.7",
                "friendsofsymfony/oauth-server-bundle": "1.4.*@dev",

* **JWT** 
    * LexicJWTAuthenticationBundle

                "lexik/jwt-authentication-bundle": "^1.3",

#### Documentación y cliente apis:

* **ApiDoc** 
    * nelmioApiDocBundle

                "nelmio/api-doc-bundle": "^2.11"


## Parte 1, Bundles contribuidos:

Instalar Symfony 2.8.* standard edition

    composer create-project symfony/framework-standard-edition practica "2.8.*"

podemos comprobar la version que hemos descargado

    php app/console --version
    Symfony version 2.8.3 - app/dev/debug

Iremos instalando y configurando, uno por uno, los módulos que necesitaremos. Para instalar el serializer 
necesitamos subir la versión de php en el composer.json antes de instalar el nuevo bundle.

    // composer.json
        ...
        "config": {
            "bin-dir": "bin",
            "platform": {
                "php": "5.6.18"
            }
        },
        ...

Ahora si, podemos instalar el bundle.

    composer require "jms/serializer-bundle" "^1.1"

Lo actvamos en el kernel

    // app/AppKernel.php
    class AppKernel extends Kernel
    {

        public function registerBundles()
        {
            $bundles = array(
              ...
              new JMS\SerializerBundle\JMSSerializerBundle(),

JmsSerializerBundle no necesita ninguna configuración inicial para funcionar, para más detalle la 
[documentacción del bundle](http://jmsyst.com/bundles/JMSSerializerBundle/master/configuration) es bastante detallada.

El siguiente bundle que instalaremos es el Rest Bundle de Friends of symfony 

    composer require friendsofsymfony/rest-bundle:^1.7

Lo activamos en el kernel y pasamos a la configuración

    # app/config/config.ymls
    ...
    fos_rest:
        param_fetcher_listener: true
        disable_csrf_role: ROLE_USER

Con esta configuración básica nos servirá, para más detalle sobre las configuraciones podemos mira la [documentación del bundle](http://symfony.com/doc/master/bundles/FOSRestBundle/configuration-reference.html).

Para evitar problemas con HTTP access control, CORS(cross-origin HTTP request), los chicos de Nelmio han creado un bundle que nos soluciona  perfectamente este problema.

Lo instalamos del modo habitual:

    composer require nelmio/cors-bundle:^1.4

Y pasamos a configurarlo, es conveniente repasar el [README](https://github.com/nelmio/NelmioCorsBundle) del bundle para revisar los parametros de configuración.

    nelmio_cors:
        defaults:
            allow_credentials: false
            allow_origin: []
            allow_headers: []
            allow_methods: []
            expose_headers: []
            max_age: 0
            hosts: []
            origin_regex: false
        paths:
            '^/api/':
                allow_origin: ['*']
                allow_headers: ['*']
                allow_methods: ['POST', 'PUT', 'GET', 'DELETE']
                max_age: 3600

Vamos con otro bundle de friends of symfony, este es uno de los bundle más utilizados por la comunidad de symfony, sin duda el principal en gestión de usuarios.

    composer require friendsofsymfony/user-bundle:^1.3

Este módulo no nos da todo echo, pero nos facilita mucha lógica en la gestion de usuarios, según el caso puede ser muy util.
De momento dejaremos la configuración basica con Doctrine ORM, pero siguiendo el patrón repositorio, abstraeremos casi completamente 
nuestra aplicación del motor de base de dato que bayamos a utilizar, el objetivo es no depender de ningún motor de bbdd en particular.

Activamos el bundle en el kernel y vamos a las configuraciones, primero activamos las traducciones

    #app/config/config.yml
    ...
    framework:
        translator: ~

Ahora necesitamos crear la Entidad Usuario, en el primer caso la crearemos en Mysql con Doctrine ORM, el bundle nos permite diferentes tipos como veremos más adelante.

    <?php

    // src/AppBundle/Entity/User.php

    namespace AppBundle\Entity;

    use FOS\UserBundle\Model\User as BaseUser;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity
     * @ORM\Table(name = "user")
     */
    class User extends BaseUser
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @var string
         * @ORM\Column(type="string", length=255)
         */
        protected $username;

        /**
         * @var string
         * @ORM\Column(type="string", length=255, unique=true )
         */
        protected $usernameCanonical;

        /**
         * @var string
         * @ORM\Column(type="string", length=255)
         */
        protected $email;

        /**
         * @var string
         * @ORM\Column(type="string", length=255, unique=true )
         */
        protected $emailCanonical;

        /**
         * @var bool
         * @ORM\Column(type="boolean")
         */
        protected $enabled;

        /**
         * The salt to use for hashing.
         *
         * @ORM\Column(type="string")
         *
         * @var string
         */
        protected $salt;

        /**
         * Encrypted password. Must be persisted.
         *
         * @ORM\Column(type="string")
         *
         * @var string
         */
        protected $password;

        /**
         * User description.
         *
         * @ORM\Column(type="text", nullable=true)
         *
         * @var string
         */
        protected $description = null;

        /**
         * Plain password. Used for model validation. Must not be persisted.
         *
         * @var string
         */
        protected $plainPassword;

        /**
         * @ORM\Column(type="datetime", nullable=true)
         *
         * @var \DateTime
         */
        protected $lastLogin;

        /**
         * Random string sent to the user email address in order to verify it.
         *
         * @ORM\Column(type="string", nullable=true)
         *
         * @var string
         */
        protected $confirmationToken;

        /**
         * @ORM\Column(type="datetime", nullable=true)
         *
         * @var \DateTime
         */
        protected $passwordRequestedAt;

        /**
         * @ORM\Column(type="boolean")
         *
         * @var bool
         */
        protected $locked = false;

        /**
         * @ORM\Column(type="boolean")
         *
         * @var bool
         */
        protected $expired = false;

        /**
         * @ORM\Column(type="datetime", nullable=true)
         *
         * @var \DateTime
         */
        protected $expiresAt;

        /**
         * @ORM\Column(type="array")
         *
         * @var array
         */
        protected $roles;

        /**
         * @ORM\Column(type="boolean")
         *
         * @var bool
         */
        protected $credentialsExpired = false;

        /**
         * @ORM\Column(type="datetime", nullable=true)
         *
         * @var \DateTime
         */
        protected $credentialsExpireAt;

Como podeís ver hemos llamado a la clase `User` esto es porque a nuestra aplicación no tiene porque importarle de donde vengan los usuarios.
Hemos mapeado los campos de la tabla user mediante las anotaciones de doctrine. 

Actualizamos nuestro security.yml

    # app/config/security.yml
    security:
        encoders:
            FOS\UserBundle\Model\UserInterface: bcrypt

        role_hierarchy:
            ROLE_ADMIN:       ROLE_USER
            ROLE_SUPER_ADMIN: ROLE_ADMIN

        providers:
            fos_userbundle:
                id: fos_user.user_provider.username

        firewalls:
            main:
                pattern: ^/
                form_login:
                    provider: fos_userbundle
                    csrf_provider: security.csrf.token_manager # Use form.csrf_provider instead for Symfony <2.4
                logout:       true
                anonymous:    true

        access_control:
            - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/admin/, role: ROLE_ADMIN }

y en el config selecionamos el tipo de base de datos, le asignamos la clase usuario, el firewall y activamos la confirmación por email(opcional).

    # app/config/config.yml
    fos_user:
        db_driver: orm 
        # other valid values are 'mongodb', 'couchdb' and 'propel'
        firewall_name: main
        user_class: AppBundle\Entity\User
        registration:
            confirmation:
                enabled: true

En el config_dev haremos que todos los email nos lleguen a nosotros mismos

    # app/config/config_dev.yml
    swiftmailer:
        delivery_address: 'tu@email.com'


Importamos las rutas

    # app/config/routing.yml
    fos_user_security:
        resource: "@FOSUserBundle/Resources/config/routing/security.xml"

    fos_user_profile:
        resource: "@FOSUserBundle/Resources/config/routing/profile.xml"
        prefix: /profile

    fos_user_register:
        resource: "@FOSUserBundle/Resources/config/routing/registration.xml"
        prefix: /register

    fos_user_resetting:
        resource: "@FOSUserBundle/Resources/config/routing/resetting.xml"
        prefix: /resetting

    fos_user_change_password:
        resource: "@FOSUserBundle/Resources/config/routing/change_password.xml"
        prefix: /profile
    

En este caso necesitaremos una bbdd para persistir nuestras tablas.

Primero generamos los getters y setters de nuestras entidades.

    php app/console doctrine:generate:entities AppBundle

creamos la bbdd

    php app/console doctrine:database:create

Y generamos el schema

    php app/console doctrine:schema:create

Ahora generamos un asuario administrador con los comandos que nos provee fos user bundle

    php app/console fos:user:create admin admin@admin.mail --super-admin ROLE_SUPER_ADMIN
    php app/console fos:user:change-password admin Demo1234

podemos comprobar las rutas que nos ha generado

    php app/console debug:router | grep fos_user
    fos_user_security_login             ANY        ANY      ANY    /login                             
    fos_user_security_check             POST       ANY      ANY    /login_check                       
    fos_user_security_logout            ANY        ANY      ANY    /logout                            
    fos_user_profile_show               GET        ANY      ANY    /profile/                          
    fos_user_profile_edit               ANY        ANY      ANY    /profile/edit                      
    fos_user_registration_register      ANY        ANY      ANY    /register/                         
    fos_user_registration_check_email   GET        ANY      ANY    /register/check-email              
    fos_user_registration_confirm       GET        ANY      ANY    /register/confirm/{token}          
    fos_user_registration_confirmed     GET        ANY      ANY    /register/confirmed                
    fos_user_resetting_request          GET        ANY      ANY    /resetting/request                 
    fos_user_resetting_send_email       POST       ANY      ANY    /resetting/send-email              
    fos_user_resetting_check_email      GET        ANY      ANY    /resetting/check-email             
    fos_user_resetting_reset            GET|POST   ANY      ANY    /resetting/reset/{token}           
    fos_user_change_password            GET|POST   ANY      ANY    /profile/change-password   

Ahora descargaremos los bundles para implementar la autorización JWT

    composer require lexik/jwt-authentication-bundle:^1.3

Lo activamos en el kernel y generamos las claves ssh

    mkdir -p app/var/jwt
    openssl genrsa -out app/var/jwt/private.pem -aes256 4096
    openssl rsa -pubout -in app/var/jwt/private.pem -out app/var/jwt/public.pem

También las de test

    openssl genrsa -out app/var/jwt/private-test.pem -aes256 4096
    openssl rsa -pubout -in app/var/jwt/private-test.pem -out app/var/jwt/public-test.pem

Ahora deberíamos añadir las rutas al par de claves, como variables de entorno(Recordemos las buenas prácticas, la información sensible 
es mejor tenerla fuera de nuestra aplicación). Si estamos utilizando el server que monta symfony con el comando server:run los meteremos en el `parameters.yml`

Primero definimos los parametros necesarios en el `parameters.yml`

    # app/config/parameters.yml.dist
    jwt_private_key_path: %kernel.root_dir%/var/jwt/private.pem   # ssh private key path
    jwt_public_key_path:  %kernel.root_dir%/var/jwt/public.pem    # ssh public key path
    jwt_key_pass_phrase:  ''                                      # ssh key pass phrase
    jwt_token_ttl:        86400

    # app/config/parameters.yml
    jwt_private_key_path: '%kernel.root_dir%/var/jwt/private.pem'
    jwt_public_key_path: '%kernel.root_dir%/var/jwt/public.pem'
    jwt_key_pass_phrase: demo
    jwt_token_ttl: ~

Ponemos los parametros definidos en sus respectivas configuraciones, primero en test

    # app/config/config_test.yml
    lexik_jwt_authentication:
        private_key_path:   %kernel.root_dir%/var/jwt/private-test.pem
        public_key_path:    %kernel.root_dir%/var/jwt/public-test.pem

Despues en el config general.

    # app/config/config.yml
    lexik_jwt_authentication:
        private_key_path: %jwt_private_key_path%
        public_key_path:  %jwt_public_key_path%
        pass_phrase:      %jwt_key_pass_phrase%
        token_ttl:        %jwt_token_ttl%

Pasamos a actualizar el `security.yml`

    # app/config/security.yml
    ...
        firewalls:
            login:
                pattern:  ^/api/login
                stateless: true
                anonymous: true
                form_login:
                    check_path:               /api/login_check
                    success_handler:          lexik_jwt_authentication.handler.authentication_success
                    failure_handler:          lexik_jwt_authentication.handler.authentication_failure
                    require_previous_session: false

            api:
                pattern:   ^/api
                stateless: true
                lexik_jwt: ~
            ...

        access_control:
            ...
            - { path: ^/api/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/api, roles: IS_AUTHENTICATED_FULLY }

Imporamos las rutas en el `eouting.yml`

    # app/config/routing.yml
    api_login_check:
        path: /api/login_check

Si utilizamos apache, borrariamos las rutas a las claves privadas, por lo menos de producción y las añadimos al 
virtualhost, para mejor rendimiento deberíamos pasar el .htaccess completo

    <VirtualHost *:80>

        ServerName dev.site.com

        DocumentRoot /var/www/symfony2/web
        <Directory /var/www/symfony2/web>
            AllowOverride All
            Require all granted
        </Directory>

        SetEnv  PRIVATE_KEY_PAIR   /var/jwt/private.pem
        SetEnv  PUBLIC_KEY_PAIR    /var/jwt/public.pem

        RewriteEngine On
        RewriteCond %{HTTP:Authorization} ^(.*)
        RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]

    </VirtualHost>

Actualizamos el `parameter.yml`

    # app/config/parameters.yml.dist
    jwt_private_key_path: %kernel.root_dir%%private.key.pair%   # ssh private key path
    jwt_public_key_path:  %kernel.root_dir%%public.key.pair%    # ssh public key path

Comprobamos que todo funciona, primero generamos el token JWT

    curl -X POST http://127.0.0.1:8000/api/login_check -d _username=admin -d _password=Demo1234
    {"token":"eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJleHAiOjE0NTc0NzQwMTAsInVzZXJuYW1lIjoiYWRtaW4iLCJpYXQiOiIxNDU3Mzg3NjEwIn0.jVeYIiZL7B50MVu-luDVJpwdkQdo7cI3rsBbZkCeDzYdLrQTv9HBVbUBzqd-e2GliOt9rThFJZd8HlmJtUcebiFxebN_fuCE3sjHtH3cg7Zu3JJt2J_Zyv8c1ANB5h7sftlbBoksfXoMO5q6VLckXPNeY9V1T3Fyq2u4jyntx4DtdGI6A-FUjhrICWhUFwZlrwURsH8Qj8bxYNC9A2xmns0_PNlIKHruRRh6Lo-dWYTTYpKanfj4XNwS_Xew9f0UDWcKAUYoBCxwdqwNSO0UAdFbao-R8dgvt9ww23iC1T0so2w1tEMuaIKfWQ7a3kI87ZWyCbjNMuFxgO47mDs35NmxtnXhJMe0LO3kpGM2sEoG5DxYA7xxmk-PHtp02-t-V4uLfbA4TyK4uKon7H8ZYFc5JnSIz1i-Ps6ETLWRSnfw9qPiXlkz9dqXATsXFsMGACNpcCwtmq0eSSkp4AB2BWEXyHWwyOLVMhLIVpZ5dO5eKRK4gWwvY0tsvgrI_354GhLBA7S1amdcsVRZi16Zz350stFCd5E0iHFsp62vJ0StuB_SzyVxSfCsOLixSNY9yoRMScw-kfZdHcvwrJcrdBWgVlF04sxIz0xEftYG4FefTO4DkANjt2z2A1JD8Ojx9rxaTaZMFIZeoegWkM8mfi0zIosMcfEWDHWumQ5AHjI"}

Nos tiene que devolver el token JWT.

Nos falta nelmio api doc bundle para dejar preparado todo el stack de nuestra aplicación, después de instalarlo, empezaremos 
a aplicar tdd para implementar el el patrón repositorio y abstraer completamente el motor de bbdd de nuestra aplicación.

    composer require nelmio/api-doc-bundle:^2.11

Lo activamos en el kernel y vamos a las configs

    nelmio_api_doc: 
        name: 'Educaedu práctica API docs'
    #    exclude_sections: ["Some section"]
        default_sections_opened:  true
    #Api Docs template
    #    motd:
    #        template: some/template.html.twig
        sandbox:
    #        enabled: false

Importamos en el `routing.yml`
    
    NelmioApiDocBundle:
        resource: "@NelmioApiDocBundle/Resources/config/routing.yml"
        prefix:   /api/doc

Añadimos un nuevo firewall para la documentación, justo antes de `api`, para evitar colisiones de firewall

    # app/config/security.yml
        ...
        firewalls:
            ...
            docs:
                pattern:  ^/api/doc
                stateless: true
                anonymous: true
            ...
        access_control:
            ...
            - { path: ^/api/doc, roles: IS_AUTHENTICATED_ANONYMOUSLY }
            ...

Y accedemos a nuestra recién creada documentación en 127.0.0.1:8000/app_dev.php/api/doc.
Ya tenemos todo lo necesario, ahora podemos empezar a desarrollar nuestra aplicación y hacer el primer commit.


## Parte 2, TDD 1:

Empezaremos con el user story 4 `Como usuario autenticado puedo obtener la información de mi perfil.`. No es el más sencillo,
pero será un buen punto de partida. Creamos el archivo `MeControllerTest`, y creamos nuestro primer test con PHPUnit. Para la 
acción get de nuestro servicio rest.

    <?php
    // src/AppBundle/Tests/Controller/MeControllerTest.php

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

Si corremos el test, es obvio, que fallará.

    kpicaza@localhost:~/server/educaedu/practica-final$ phpunit -c app/
    PHPUnit 4.8.23 by Sebastian Bergmann and contributors.

    .F

    Time: 666 ms, Memory: 35.25Mb

    There was 1 failure:

    1) AppBundle\Tests\Controller\MeControllerTest::testValidGetMe
    Failed asserting that 404 matches expected 200.

    /educaedu/practica-final/src/AppBundle/Tests/Controller/MeControllerTest.php:49

    FAILURES!
    Tests: 2, Assertions: 3, Failures: 1.

Esto está muy bien, la información de este test, es nuestro siguiente paso a seguir, necesitamos una ruta y un 
controlador. Primero crearemos nuestro controlador dentro del AppBundle.

    <?php
    // src/AppBundle/Controller/MeController

    namespace AppBundle\Controller;

    use FOS\RestBundle\Controller\FOSRestController;
    use Nelmio\ApiDocBundle\Annotation\ApiDoc;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\HttpFoundation\Request;

    /**
     * MeController.
     */
    class MeController extends FOSRestController
    {
        /**
         * @Security("is_granted('view', user)")
         * @ApiDoc(
         *   description = "Get your own user.",
         *   statusCodes = {
         *     200 = "Show user info.",
         *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
         *     403 = "Authorizationi failure, user doesn’t have permission to access this area.",
         *   }
         * )
         * 
         * @return json|xml
         */
        public function getMeAction(Request $request)
        {
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $view = $this->view($user);

            return $this->handleView($view);
        }

    }

Damos de alta la ruta en el `routin.yml`
   
    # app/config/routing.yml
    ...
    app_user:
        type: rest
        prefix: /api
        resource: AppBundle\Controller\MeController
    ...

Por ultimo creamos el usuario de test.

    php app/console fos:user:create
    Please choose a username:meco
    Please choose an email:meco@meco.mail
    Please choose a password:Demo1234
    Created user meco

    php app/console fos:user:promote meco VIEW

Ahora corremos los tests. Seguimos en rojo, no tenemos permiso para acceder a esa url, está protegida por JWT token, además
hemos puesto la anotacion `Security`. Si nos fijamos en el error, es un 403, como hemos visto en el curso es un fallo de autorización,
esto es porque nuestro user tiene ambos roles `ROLE_USER` y `VIEW` pero todavía no hemos implementado ninguna politica de seguridad.

Para esto crearemos una clase `Voter`, dentro de el directorio `Security`

    <?php
    // src/AppBundle/Security/UserVoter.php

    namespace AppBundle\Security;

    use Symfony\Component\Security\Core\Authorization\Voter\Voter;
    use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
    use AppBundle\Entity\User;

    /**
     * UserVoter.
     */
    class UserVoter extends Voter
    {
        const VIEW = 'view';

        public function supports($attribute, $subject)
        {
            return $subject instanceof User && in_array($attribute, array(
                  self::VIEW
            ));
        }

        protected function voteOnAttribute($attribute, $currentUser, TokenInterface $token)
        {
            $user = $token->getUser();

            if (!$user instanceof User) {
                return false;
            }

            $roles = $user->getRoles();

            if (
                in_array('ROLE_USER', $roles) &&
                $attribute == self::VIEW &&
                // User only can view own info.
                $user->getEmail() === $currentUser->getEmail()
            ) {
                return true;
            }

            return false;
        }
    }

Como hemos explicado antes, los voter son la forma adecuada de permitir accesos a deferentes arte de la 
aplicación, todavía nos falta dar de alta el voter en el `services.yml`.

    # app/config/services.yml
    services:
        ...
        security.access.user_voter:
            class:      AppBundle\Security\UserVoter
            public:     false
            tags:
               - { name: security.voter }

Ahora los test está en verde, es un buen momento para comitear, pero no es más que un falso positivo. Como podemos 
ver, nuestro controlador está directamente acoplado con el motor de Base de datos, en este caso MySQL.


## Parte 3, Repository pattern 1 y TDD 2:

Como hemos visto durante el curso uno de los objetivos principales es desacoplar lo máximo posíble nuestro código, para 
hacerlo reutilizable. Para lograr esto seguiremos los patrones de diseño que vimos antes, en particular del patrón repositorio.
Necesitamos abstraer la capa de base de datos del resto de la aplicación, empezaremos creando un test para nuestro repositorio

    <?php
    // src/Tests/Model/UserRepositoryTest.php

    namespace AppBundle\Tests\Model;

    use AppBundle\Entity\User;
    use AppBundle\Entity\UserGateway;
    use AppBundle\Model\UserFactory;
    use AppBundle\Model\UserRepository;
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
            $gatewayClassname = 'AppBundle\Entity\UserGateway';
            $this->gateway = $this->prophesize($gatewayClassname);
            $this->factory = new UserFactory();
            $this->repository = new UserRepository($this->gateway->reveal(), $this->factory);
        }
    }

Si nos fijamos en el método `setUp()`, para crear el repositorio, necesitamos tres clases, La factoría o `Factory`, la puerta de enlace
o `Gateway` y el repositorio o `Repository`. vamos a añadir el test al final de la clase.

    <?php
    // src/Tests/Model/UserRepositoryTest.php
        ...
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
        }
    }

Pasamos los test, y nos encontramos la primera escepción, la clase `UserFactory` no existe, vamos a crearla. Bueno, paremos un segundo y pensemos,
Si queremos seguir el principio de inversión de dependencias necesitamos abstraer nuestra clases, para ello haremos uso de `Interfaces` o contratos
que definirán la estructuras que deben recibir las clases de nivel superior, en nuestro caso el controlador. Creamos nuestro UserFactoryInterface en 
el directorio `Model`.

    <?php
    // src/AppBundle/Model/UserFactoryInterface.php

    namespace AppBundle\Model;
    /**
     * UserFactoryInterface.
     */
    interface UserFactoryInterface
    {
        /**
         * @param \AppBundle\Model\UserInterface $rawUser
         *
         * @return \AppBundle\Model\UserInterface
         */
        public function makeOne(UserInterface $rawUser);
        /**
         * @param array $rawUsers
         *
         * @return array
         */
        public function makeAll(array $rawUsers);
        /**
         * @param \AppBundle\Model\UserInterface $rawUser
         *
         * @return \AppBundle\Model\UserInterface
         */
        public function make(UserInterface $rawUser);
    }

Creamos también `GatewayInterface` y `UserInterface`, queremos una abstracción total del motor de base de datos y sabemos que las clases
`User` y `UserGateway`, dependen directamente del motor de base de datos.

    <?php
    // src/AppBundle/Model/UserGatewayInterface.php

    namespace AppBundle\Model;
    /**
     * UserGateway.
     */
    interface UserGatewayInterface
    {
        
    }

Creamos el `Interface` del el objeto user de `FosUserBundle`, en este caso lo utilizaremos como está, pero quien sabe si el día de 
mañana tenemos que cambiar de framework?

    <?php

    // src/AppBundle/Model/User.php

    namespace AppBundle\Model;

    use FOS\UserBundle\Model\GroupInterface;

    /**
     * User.
     */
    interface UserInterface
    {
        /**
         * @param array array().
         */
        public static function fromArray(array $array = array());

        public function __construct();

        public function addRole($role);

        /**
         * Serializes the user.
         *
         * The serialized data have to contain the fields used by the equals method and the username.
         *
         * @return string
         */
        public function serialize();

        /**
         * Unserializes the user.
         *
         * @param string $serialized
         */
        public function unserialize($serialized);

        /**
         * Removes sensitive data from the user.
         */
        public function eraseCredentials();

        /**
         * Returns the user unique id.
         *
         * @return mixed
         */
        public function getId();

        public function getUsername();

        public function getUsernameCanonical();

        public function getSalt();

        public function getDescription();

        public function getEmail();

        public function getEmailCanonical();

        /**
         * Gets the encrypted password.
         *
         * @return string
         */
        public function getPassword();

        public function getPlainPassword();

        /**
         * Gets the last login time.
         *
         * @return \DateTime
         */
        public function getLastLogin();

        public function getConfirmationToken();

        /**
         * Returns the user roles.
         *
         * @return array The roles
         */
        public function getRoles();

        /**
         * Never use this to check if this user has access to anything!
         *
         * Use the SecurityContext, or an implementation of AccessDecisionManager
         * instead, e.g.
         *
         *         $securityContext->isGranted('ROLE_USER');
         *
         * @param string $role
         *
         * @return bool
         */
        public function hasRole($role);

        public function isAccountNonExpired();

        public function isAccountNonLocked();

        public function isCredentialsNonExpired();

        public function isCredentialsExpired();

        public function isEnabled();

        public function isExpired();

        public function isLocked();

        public function isSuperAdmin();

        public function isUser();

        public function removeRole($role);

        public function setUsername($username);

        public function setUsernameCanonical($usernameCanonical);

        /**
         * @param \DateTime $date
         *
         * @return User
         */
        public function setCredentialsExpireAt(\DateTime $date);

        /**
         * @param bool $boolean
         *
         * @return User
         */
        public function setCredentialsExpired($boolean);

        public function setDescription($description);

        public function setEmail($email);

        public function setEmailCanonical($emailCanonical);

        public function setEnabled($boolean);

        /**
         * Sets this user to expired.
         *
         * @param bool $boolean
         *
         * @return User
         */
        public function setExpired($boolean);

        /**
         * @param \DateTime $date
         *
         * @return User
         */
        public function setExpiresAt(\DateTime $date);

        public function setPassword($password);

        public function setSuperAdmin($boolean);

        public function setPlainPassword($password);

        public function setLastLogin(\DateTime $time);

        public function setLocked($boolean);

        public function setConfirmationToken($confirmationToken);

        public function setPasswordRequestedAt(\DateTime $date = null);

        /**
         * Gets the timestamp that the user requested a password reset.
         *
         * @return null|\DateTime
         */
        public function getPasswordRequestedAt();

        public function isPasswordRequestNonExpired($ttl);

        public function setRoles(array $roles);

        /**
         * Gets the groups granted to the user.
         *
         * @return Collection
         */
        public function getGroups();

        public function getGroupNames();

        public function hasGroup($name);

        public function addGroup(GroupInterface $group);

        public function removeGroup(GroupInterface $group);

        public function __toString();
    }

Modificamos la clase `User` para que implemente `UserInterface`

    <?php

    // src/AppBundle/Entity/User.php

    namespace AppBundle\Entity;

    use AppBundle\Model\UserInterface;
    use FOS\UserBundle\Model\User as BaseUser;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity
     * @ORM\Table(name = "user")
     * @ORM\Entity(repositoryClass="AppBundle\Entity\UserGateway")
     */
    class User extends BaseUser implements UserInterface
    {



creamos sus respectivas implementaciones, `UserGateway` la situaremos junto con `User` dentro de la carpeta `Entity`

    <?php
    // src/AppBundle/Entity/UserGateway

    namespace AppBundle\Entity;
    use AppBundle\Model\UserInterface;
    use AppBundle\Model\UserGatewayInterface;
    use Doctrine\ORM\EntityRepository;
    /**
     * UserGateway.
     */
    class UserGateway extends EntityRepository implements UserGatewayInterface
    {

    }

Y por último la clase `Factory` dentro del directorio `Model`

    <?php
    // src/AppBundle/Model/UserFactory

    namespace AppBundle\Model;

    use AppBundle\Model\UserInterface;
    use AppBundle\Model\UserFactoryInterface;
    /**
     * UserFactory implements UserFactoryInterface.
     */
    class UserFactory implements UserFactoryInterface
    {
        /**
         * @param \AppBundle\Entity\User $rawUser
         *
         * @return \AppBundle\Entity\User
         */
        public function makeOne(UserInterface $rawUser)
        {
            return $this->make($rawUser);
        }
        /**
         * @param \AppBundle\Entity\User $rawUser
         *
         * @return \AppBundle\Entity\User
         */
        public function make(UserInterface $rawUser)
        {
            // You can format object, in this case we left it to return as raw object, feedback is welcome!
            return $rawUser;
        }
    }

ya os habeís fijado que el user gateway y su interface están vacios, es porque de momento utilizaremos los metodos de doctrine
Ahora necesitamos implementar la configuración para atar todas estas piezas, Symfony nos ofrece la inyección de dependencias como solución,
veamos como se hace, abrimos el archivo `services.yml`

    services:
        ...
        app.user_factory:
            class: AppBundle\Entity\UserFactory

        app.user_gateway:
            class: AppBundle\Entity\UserGateway
            factory: [ "@doctrine", getRepository]
            arguments: [ "AppBundle:User" ]

        app.user_repository:
            class: AppBundle\Entity\UserRepository
            arguments: [ "@app.user_gateway", "@app.user_factory" ]

Y para terminar de implementar el patrón repositorio solo nos falta la clase `UserRepository`

    <?php
    namespace AppBundle\Model;
    use AppBundle\Model\UserGatewayInterface;
    use AppBundle\Model\UserFactoryInterface;
    /**
     * UserRepository.
     */
    class UserRepository
    {
        /**
         * @var \AppBundle\Model\UserGatewayInterface
         */
        private $gateway;
        /**
         * @var \AppBundle\Model\UserFactoryInterface
         */
        private $factory;
        /**
         * @param \AppBundle\Model\UserGatewayInterface $gateway
         * @param \AppBundle\Model\UserFactoryInterface $factory
         */
        public function __construct(UserGatewayInterface $gateway, UserFactoryInterface $factory)
        {
            $this->gateway = $gateway;
            $this->factory = $factory;
        }
        /**
         * @param User|int $id
         *
         * @return User
         */
        public function find($id)
        {
            return $this->gateway->find($id);
        }
        /**
         * @param array $criteria
         * @param array $orderBy
         *
         * @return User
         *
         * @throws NotFoundHttpException
         */
        public function findOneBy(array $criteria, array $orderBy = array())
        {
            $user = $this->gateway->findOneBy($criteria, $orderBy);
            if (null === $user) {
                return null;
            }
            return $this->factory->makeOne($user);
        }
        /**
         * @param User $user
         *
         * @return User
         */
        public function parse(UserInterface $user)
        {
            return $this->factory->makeOne($user);
        }
    }

Pasamos los tests, y tenemos que ver que está todo correcto, ahora podemos comitear. El siguiente paso es unir el controlador 
con el modelo, lo harremos de la siguiente manera

Actualizamos `MeController` para que utilice nuestro `Repository` y así lo desacoplamos del motor de base de datos

        public function getMeAction()
        {
            $user = $this->get('app.user_repository')->find(
                $this->container->get('security.token_storage')->getToken()->getUser()->getId()
            );
            $view = $this->view($user);

            return $this->handleView($view);
        }

¿Como? hemos añadido una line más, es algo conceptual, la diferencia, es que antes nuestro controlador solo podía funcionar dependiendo 
directamente de doctrine, de esta manera, el Authorization Provider nos devueve la entidad usuario a partir del Token JWT, pero podríamos 
no querer mostrar el objeto tal cual está, seguramente nos interese mostrar la información filtrado o procesada. Otro ejemplo de porque hacemos 
esto, sería si como en nuestro caso estamos haciendo un servicio rest, si no tubiesemos FosRestBundle(Quien se encarga de formatear nuestras respuestas),
sería nuestro reposittorio el encargado de enviar los datos formateados al controlador.

Si volvemos a pasar los test todo debe seguir funcionando correctamente y podemos volver a commitear nuestro trabajo.

## Parte 4, 2º user Story

Las verdad que al realizar el primer user story, nos hemos dejado todo bastante bien organizado para que sea mas sencillo continuar con los siguiente, 
igualmente, todavía faltan piezas importantes del puzzle.

comenzaremos implementando el user story 2 `Como usuario sin autenticar puedo registrarme en el site`, este user story, a parte de lo que ya tenemos 
creado necesita un formulario y validación. empecemos por los tests. primero crearemos el método post, justo despues del método `createAuthenticatedClient` que nos ayudará a realizar las peticiones en 
los diferentes tests.

    <?php 
    // src/AppBundle/Tests/Controller/MeControllerTest.php
    
    class MeControllerTest extends WebTestCase
    {
        ...
        protected function post($uri, array $data, $auth = false)
        {
            $client = $this->getClient($auth);
            $client->request('POST', $uri, $data);
            return $client->getResponse();
        }

Y añadimos nuestro primer test para el registro, testeara el envío del formulario vacio, nos tiene que devolver un error 400.

    <?php 
    // src/AppBundle/Tests/Controller/MeControllerTest.php
    
    class MeControllerTest extends WebTestCase
    {
        const REGISTER_ROUTE = '/api/register/me.json';
        ...
        public function testRegistrationFailedWithEmptyForm()
        {
            $client = static::createClient();
            $client->request('POST', self::REGISTER_ROUTE);
            $this->assertEquals(400, $client->getResponse()->getStatusCode());
        }

Pasamos los test, y obtenemos un 404 en vez del 400 que esperamos, al igual que en nuestro primer user story, necesitaremos una 
ruta a un controlador, y en este caso además necesitaremos un formulario, vamos con el controlador

    <?php
    // src/AppBundle/Controller/MeController.php

    /**
     * @Route("/api/register/me.{_format}", methods="POST")
     * @ApiDoc(
     *   description = "Register new user.",
     *   statusCodes = {
     *     200 = "User correctly added.",
     *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
     *   },
     *   requirements={
     *      {
     *          "name"="_format",
     *          "dataType"="string",
     *          "requirement"="json|xml|html",
     *      }
     *   }
     * )
     * 
     * @param Request $request
     *
     * @return array
     */
    public function MeAction(Request $request)
    {
        $user = $request->request->all();
        
        $view = $this->view($user);
        return $this->handleView($view);
    }

Vemos que en este caso hemos utilizado la anotación `@Route`, esto es porque la ruta que genera `FosRest` para este caso dería algo como 
`/api/mes.{_format}`, en este caso esta ruta romería la coherencia de nuestro servicio rest, también podemos ver que no estamos haciendo 
uso de formularios, esto será lo siguiente que hagamos. Para crear formularios y validarlos crearemos dos nuevas clases. antes de ello le 
diremos a nuestro API firewall, que la ruta que acabamos de crear será accesible sin necesidad de autenticación.

    # app/config/security.yml
    security:
        ...
        firewalls:
            ...
            register:
                pattern:  ^/api/register/me.json
                anonymous: true

        ...
        access_control:
            ...
            - { path: ^/api/register/me.json, roles: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/api, roles: IS_AUTHENTICATED_FULLY }

Ahora si pasamos a crear nuestro formulario, ara la validación crearemos un simple modelo de formulario, este a su vez nos ayudará a documentar el api,
veamos como

    <?php
    namespace AppBundle\Form\Model;
    use Symfony\Component\Validator\Constraints as Assert;
    /**
     * RegistrationFormModel.
     */
    class RegistrationFormModel
    {
        const NAME = 'username';
        const MAIL = 'email';
        const PLAIN = 'plainPassword';
        const PASS = 'password';
        /**
         * @Assert\NotBlank()
         * @Assert\Regex("/[a-zA-Z0-9]/")
         *
         * @var string
         */
        protected $username;
        /**
         * @Assert\NotBlank()
         * @Assert\Email()
         *
         * @var string
         */
        protected $email;
        /**
         * @Assert\NotBlank()
         *
         * @var string
         */
        protected $plainPassword;
        /**
         * @Assert\NotBlank()
         *
         * @var string
         */
        protected $password;
        public function __construct($username = null, $email = null, $plainPassword = null, $password = null)
        {
            $this->username = $username;
            $this->email = $email;
            $this->plainPassword = $plainPassword;
            $this->password = $password;
        }
        /**
         * @param array $user
         *
         * @return \self
         */
        public static function fromArray(array $user = array(self::NAME => null, self::MAIL => null, self::PLAIN => null, self::PASS => null))
        {
            return new self(
                array_key_exists(self::NAME, $user) ? $user[self::NAME] : null,
                array_key_exists(self::MAIL, $user) ? $user[self::MAIL] : null,
                array_key_exists(self::PLAIN, $user) ? $user[self::PLAIN] : null,
                array_key_exists(self::PASS, $user) ? $user[self::PASS] : null
            );
        }
        public function setUsername($username)
        {
            $this->username = $username;
        }
        public function getUsername()
        {
            return $this->username;
        }
        public function setEmail($email)
        {
            $this->email = $email;
        }
        public function getEmail()
        {
            return $this->email;
        }
        public function setPlainPassword($plainPassword)
        {
            $this->plainPassword = $plainPassword;
        }
        public function getPlainPassword()
        {
            return $this->plainPassword;
        }
        public function setPassword($password)
        {
            $this->password = $password;
        }
        public function getPassword()
        {
            return $this->password;
        }
    }

La parte más interesante de esta clase son las anotaciones escritas sobre la declaración de las variables, de esta manerá añadimos la capa de 
validación al formulario por ejemplo las anotaciones NotBlack y Regex

        /**
         * @Assert\NotBlank()
         * @Assert\Regex("/[a-zA-Z0-9]/")
         *

La primera obliga a que el campo no esté vacio en ningún caso, y la segunda, implementa una expresión regular que fuerza a que el texto tan solo 
contenga caracteres alfanumericos, sin ningún tipo de símbolo, creamos el formulario para el modelo

    <?php
    // src/AppBundle/Form/Type/RegistrationFormType.php

    namespace AppBundle\Form\Type;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    /**
     * RegistrationFormType.
     */
    class RegistrationFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('username', Type\TextType::class)
                ->add('email', Type\EmailType::class)
                ->add('plainPassword', Type\PasswordType::class)
                ->add('password', Type\PasswordType::class)
            ;
        }
        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(array(
              'data_class' => 'AppBundle\Form\Model\RegistrationFormModel',
              'csrf_protection' => false,
            ));
        }
        public function getBlockPrefix()
        {
            return 'app_user_registration';
        }
    }

Es un formulario muy simple de login, con los campos minimos para crear un usuario. Ahora le tenemos que decir a nuestro controlador que empiece a utilizarlo.
Para ello utilizaremos las anotaciones de `nelmioApiDocs`, y forzaremos el envio del formulario.

    <?php
    // src/AppBundle/Controller/MeController.php
    ...
    use AppBundle\Form\Type\RegistrationFormType;
    use AppBundle\Form\Model\RegistrationFormModel;
    ...
        /**
         * @Route("/api/register/me.{_format}", methods="POST")
         * @ApiDoc(
         *   description = "Register new user.",
         *   input = "AppBundle\Form\Model\RegistrationFormModel",
         *   output = "AppBundle\Model\UserInterface",
         *   statusCodes = {
         *     200 = "User correctly added.",
         *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
         *   },
         *   requirements={
         *      {
         *          "name"="_format",
         *          "dataType"="string",
         *          "requirement"="json|xml|html",
         *      }
         *   }
         * )
         * 
         * @param Request $request
         *
         * @return array
         */
        public function MeAction(Request $request)
        {
            $user = null;
            $form = $this->createForm(RegistrationFormType::class, new RegistrationFormModel(), array('method' => 'POST'));

            $form->submit($request->request->all());

            if ($form->isValid()) {
                try {
                    $rawUser = $this->insertFromForm($form->getData());
                    $user = $this->repository->insert($rawUser);
                    $view = $this->view($user);
                    return $this->handleView($view);
                } catch (\Exception $ex) {
                    //  throw new $ex;
                    $form->addError(new FormError('Duplicate entry for email or username.'));
                    // log this somewhere.
                }
            }
            $view = $this->view($form);
            return $this->handleView($view);
        }

Si pasamos los test estaríamos de nuevo en verde, pero como en el primer user story, esto no es más que un falso positivo, 
crearemos otro test para comprobar los registros validos, para ello crearemos el método get client, para selecionar si 
queremos un cliente autenticado o no

    <?php 
    // src/AppBundle/Tests/Controller/MeControllerTest.php
    
    class MeControllerTest extends WebTestCase
    {
        const MAIL = 'meco@mail.com';
        ...
        protected function getClient($auth = false)
        {
            if (true === $auth) {
                $client = $this->createAuthenticatedClient(self::NAME, self::PASS);
            } else {
                $client = static::createClient();
            }
            return $client;
        }
        ...
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
Si volvemos a paser los tests, vemos que tenemos una clase que existe, podríamos crearla en el mismo controlador, pero 
para tener todo mejor organizado, y dejar un fina capa de controladores crearemos un handler para recivir sus valores. Primero 
definiremos su interface

    <?php
    namespace AppBundle\Handler;
    use AppBundle\Model\UserInterface;
    /**
     * ApiHandleInterface.
     */
    interface ApiUserHandlerInterface
    {
        /**
         * Get user from repository.
         * 
         * @param User $user
         */
        public function get(UserInterface $user);
        /**
         * Insert User to repository.
         * 
         * @param array $params
         */
        public function post(array $params);
    }

Para después definimos su implementación

    <?php
    namespace AppBundle\Handler;
    use AppBundle\Model\UserRepository;
    use AppBundle\Model\UserInterface;
    use AppBundle\Form\Type\RegistrationFormType;
    use AppBundle\Form\Model\RegistrationFormModel;
    use Symfony\Component\Form\FormFactoryInterface;
    use Symfony\Component\Form\FormError;
    /**
     * ApiUserHandler.
     */
    class ApiUserHandler implements ApiUserHandlerInterface
    {
        /**
         * @var UserRepository
         */
        protected $repository;
        /**
         * @var FormFactoryInterface
         */
        protected $formFactory;
        /**
         * Init Handler.
         * 
         * @param UserRepository       $repository
         * @param FormFactoryInterface $formFactory
         */
        public function __construct(UserRepository $repository, FormFactoryInterface $formFactory)
        {
            $this->repository = $repository;
            $this->formFactory = $formFactory;
        }
        /**
         * Get user from repository.
         * 
         * @param User $user
         *
         * @return User
         */
        public function get(UserInterface $user)
        {
            return $this->repository->parse($user);
        }
        /**
         * Insert User to repository.
         * 
         * @param array $params
         *
         * @return User
         */
        public function post(array $params)
        {
            $userModel = RegistrationFormModel::fromArray($params);
            $form = $this->formFactory->create(RegistrationFormType::class, $userModel, array('method' => 'POST'));
            $form->submit($params);
            if ($form->isValid()) {
                try {
                    $rawUser = $this->insertFromForm($form->getData());
                    $user = $this->repository->insert($rawUser);
                    return $this->repository->parse($user);
                } catch (\Exception $ex) {
                    //  throw new $ex;
                    $form->addError(new FormError('Duplicate entry for email or username.'));
                    // log this somewhere.
                }
            }
            return $form;
        }
        /**
         * @param ProfileFormModel $userModel
         *
         * @return User
         */
        protected function insertFromForm(RegistrationFormModel $userModel)
        {
            $user = $this->repository->findNew();
            $user
                ->setUsername($userModel->getUsername())
                ->setUsernameCanonical($userModel->getUsername())
                ->setPlainPassword($userModel->getPlainPassword())
            ;
            return $this->fromForm($user, $userModel);
        }
        /**
         * @param User             $user
         * @param ProfileFormModel $userModel
         *
         * @return User
         */
        protected function fromForm(UserInterface $user, RegistrationFormModel $userModel)
        {
            $user
                ->setEmailCanonical($userModel->getEmail())
                ->setEmail($userModel->getEmail())
            ;
            return $user;
        }
    }

Lo damos de alta como servicio en el `services.yml`

    # spp/config/services.yml
        app.api_user_handler: 
            class: AppBundle\Handler\ApiUserHandler
            arguments: [ "@app.user_repository", "@form.factory" ]

Y actualizamos el controlador

    // src/AppBundle/MeController.php
    ...
        public function MeAction(Request $request)
        {
            $user = $this->container->get('app.api_user_handler')->post(
                $request->request->all()
            );
            $view = $this->view($user);
            return $this->handleView($view);
        }

Ahora debemos añadir varios metodos a nuestro gateway y repository, empezzaremos por actualizar `GatewayInterface`

    <?php
    // src/AppBundle/Model/GatewayInterface.php

    namespace AppBundle\Model;
    /**
     * UserGateway.
     */
    interface UserGatewayInterface
    {
        /**
         * @param User $user
         *
         * @return User
         */
        public function apiInsert(UserInterface $user);

        /**
         * @return type
         */
        public function findNew();

        /**
         * @param User $user
         *
         * @return User
         */
        public function insert(UserInterface $user);
    }

Y añadimos los metodos al repository y al gateway respectivamente

    <?php
    // src/AppBundle/Entity/Gateway.php

    namespace AppBundle\Entity;

    use AppBundle\Model\UserInterface;
    use AppBundle\Model\UserGatewayInterface;
    use Doctrine\ORM\EntityRepository;

    /**
     * UserGateway.
     */
    class UserGateway extends EntityRepository implements UserGatewayInterface
    {
        /**
         * @param User $user
         *
         * @return User
         */
        public function apiInsert(UserInterface $user)
        {
            $user
                ->setEnabled(true)
                ->setExpired(false)
                ->setLocked(false)
                ->addRole('read')
                ->addRole('view')
                ->addRole('edit')
                ->addRole('ROLE_USER')
                ->addRole('ROLE_API_USER')
            ;
            return self::insert($user);
        }

        /**
         * @return type
         */
        public function findNew()
        {
            return User::fromArray();
        }
        /**
         * @param User $user
         *
         * @return User
         */
        public function insert(UserInterface $user)
        {
            $this->_em->persist($user);
            $this->_em->flush();
            return $user;
        }
    }

Y por último actualizamos nuestro repository

    // src/AppBundle/Model/UserRepository
    ...
        /**
         * @return User
         */
        public function findNew()
        {
            return $this->gateway->findNew();
        }
        /**
         * @param User $user
         *
         * @return User
         */
        public function insert(UserInterface $user)
        {
            $rawUser = $this->gateway->apiInsert($user);
            return $this->factory->makeOne($rawUser);
        }

Si pasamos ahora los test, fallarán, porque el usuario qu estmaos intentando crear está ya en la bbdd, 
así que vamos a crear la accion de borrar usuario para recuperar nuestros tests,

    // src/AppBundle/tests/MeControllerTest
        ...
        public function testDeleteMe()
        {
            $client = $this->createAuthenticatedClient(self::NAME, self::PASS);
            $client->request('DELETE', self::ROUTE);
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
        }

creamos el método delete en el controlador

    // src/AppBundle/Controller/MeController.php
        ...
        /**
         * @Security("is_granted('edit', user)")
         * @ApiDoc(
         *   description = "Delete own user.",
         *   statusCodes = {
         *     204 = "Do no return nothing.",
         *     401 = "Authentication failure, user doesn’t have permission or API token is invalid or outdated.",
         *   }
         * )
         * 
         * @return array
         */
        public function deleteMeAction()
        {
            $this->container->get('app.api_user_handler')->delete(
                $this->container->get('security.token_storage')->getToken()->getUser()
            );
            $view = $this->view(array());
            return $this->handleView($view);
        }

Después nuestro handler

    // src/AppBundle/Handler/ApiUserHandler.php
    ...
        /**
         * Delete User.
         * 
         * @param User $user
         */
        public function delete(UserInterface $user)
        {
            $this->repository->remove($user);
        }

Y añadimos los metodos a nuestro modelo, primero al interface

    // src/AppBundle/Model/GatewayInterface.php
    ...
        /**
         * @param User $user
         */
        public function remove(UserInterface $user);

Luego en la clase

    // src/AppBundle/Entity/Gateway.php
    ...
        /**
         * @param User $user
         */
        public function remove(UserInterface $user)
        {
            $this->_em->remove($user);
            $this->_em->flush();
        }

y por último en el repository

    // src/AppBundle/Model/UserRepository.php
    ...
        /**
         * @param User $user
         */
        public function remove(UserInterface $user)
        {
            $this->gateway->remove($user);
        }

si pasamos los test tendremos un 403, por el usuario auque autenticado, no tiene un voter que le permita el acceso. Vamos a añadir la entrada edit al voter que creamos antes.

Para terminar, comprobaremos la covertura que estamos dando a nuestro código con la herramienta code coverage de phpunit.

    phpunit -c app/ --coverage-html ./web/coverage # Para verlo en la intefaz gráfico o
    phpunit -c app/ --coverage-text # para ver en informe en consola.

## Site 2

No tiene porque ser symfony, podría servir cualquier aplicación que consuma el API, AngularJS, Backbone, Ember, etc...
De momento utilizaremos el sandbox que generamos con NelmioApiDocBundle.
