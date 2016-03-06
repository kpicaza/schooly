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

Debemos crear un firewall para nuestro api, ya que más tarde lo utilizaremos para securizarla.

        firewalls:
            ...
            api:
                pattern:   ^/api
                stateless: true

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




## Site 2

No tiene porque ser symfony, podría servir cualquier aplicación que consuma el API, AngularJS, Backbone, Ember, etc...
De momento utilizaremos el sandbox que generamos con NelmioApiDocBundle.
