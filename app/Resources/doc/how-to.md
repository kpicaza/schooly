 Práctica Symfony - Rest API, Oauth2, JWT, repository pattern.
===============================================================


## Aplicación de práctica:

Lo importante en este caso no es desarrollar una lógica extraordinaria, el objetivo reside en comprender las diferentes secciones que hemos analizado durante la parte teórica.

Por esta razón, nuestra aplicación simplemente mostrará al usuario un listado de libros (Título, autor, precio, portada, descripción...), este podrá ver en detalle el libro y podrá valorar el libro en caso de ser un usuario autenticado. Por otro lado el administrador podrá gestionar usuarios y libros mediante el clásico CRUD.

Crearemos dos aplicaciones, la primera será la parte de servidor, dispondrá de un servicio Rest API, con autenticación JWT y/o Oauth2 y un backoffice implementado con el nuevo Bundle EasyAdmin creado por (Javier Egiluz)[https://github.com/javiereguiluz].

La segunda será simlemente el cliente para el api, en la segunda parte lo más importante se podría decir que es la autenticación del usuario, y la comunicación con el servicio rest, dejando a un lado el tema apariencia.

Esto le haremos mediante Desarrollo dirigido por tests (**T**est **D**riven **D**evelopment), respetando las buenas prácticas de Symfony e implementando el patrón repositorio, todo esto para mejorar la escalabilidad de la aplicación, ¿quien sabe hasta donde puede llegar?

Primero definiremos los user storys.

### User stories

1. **Site 1:** Como usuario developer puedo acceder a la documentación y el sandbox del API.
1. **Site 2:** Como usuario sin autenticar puedo ver listados de libros.
1. **Site 2:** Como usuario sin autenticar puedo ver el detalle de libro.
1. **Site 2:** Como usuario sin autenticar puedo registrarme en el site.
1. **Site 2:** Como usuario sin autenticar puedo puedo acceder a la página de login.
1. **Site 2:** Como usuario autenticado puedo ver listados de libros.
1. **Site 2:** Como usuario autenticado puedo ver el detalle de libro.
1. **Site 2:** Como usuario autenticado puedo valorar de 0 a 5 de libro.
1. **Site 1:** Como usuario administrador puedo gestionar[*] usuarios registrados.
1. **Site 1:** Como usuario administrador puedo gestionar libros.

[1] *Gestionar*: me refiero a listar, crear, editar y borrar. 

Una vez definidos los `user stories`, pasamos a diferenciar la distintas parte de la aplicación en su conjunto:

### Entidades

* User
    * FOSUserBundle
* Book

### ROLES

* ROLE_USER
* ROLE_DEVELOPER
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
                "gesdinet/jwt-refresh-token-bundle": "^0.1.4",

#### Documentación y cliente apis:

* **ApiDoc** 
    * nelmioApiDocBundle

                "nelmio/api-doc-bundle": "^2.11"


## Site 1 

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
    class ORMUser extends BaseUser
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

Como podeís ver hemos llamado a la clase `ORMUser` esto es porque a nuestra aplicación no tiene porque importarle de donde bengan los usuarios.
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

    fos_user:
        db_driver: orm 
        # other valid values are 'mongodb', 'couchdb' and 'propel'
        firewall_name: main
        user_class: AppBundle\Entity\ORMUser
        registration:
            confirmation:
                enabled: true

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

Primero creamos la bbdd

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

Debemos crear un firewall para nuestro api, ya que más tarde lo utilizaremos para securizarla.

        firewalls:
            ...
            api:
                pattern:   ^/api
                stateless: true


## Site 2

No tiene porque ser symfony, podría servir cualquier aplicación que consuma el API, AngularJS, Backbone, Ember, etc...
De momento utilizaremos el sandbox que generamos con NelmioApiDocBundle.
