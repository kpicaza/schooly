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

1. **Site 1:** Como usuario administrador puedo gestionar[*] usuarios registrados.
1. **Site 1:** Como usuario administrador puedo gestionar libros.
1. **Site 1:** Como usuario developer puedo acceder a la documentación y el sandbox del API.
1. **Site 2:** Como usuario sin autenticar puedo ver listados de libros.
1. **Site 2:** Como usuario sin autenticar puedo ver el detalle de libro.
1. **Site 2:** Como usuario sin autenticar puedo registrarme en el site.
1. **Site 2:** Como usuario sin autenticar puedo puedo acceder a la página de login.
1. **Site 2:** Como usuario autenticado puedo ver listados de libros.
1. **Site 2:** Como usuario autenticado puedo ver el detalle de libro.
1. **Site 2:** Como usuario autenticado puedo valorar de 0 a 5 de libro.

[1] *Gestionar*: me refiero a listar, crear, editar y borrar. 

Una vez definidos los `user stories`, pasamos a diferenciar la distintas parte de la aplicación en su conjunto:

### Entidades

* User
    * FOSUserBundle
* Book

### Objetos de valor

* Money
* Vote

### Rest API:

#### Autenticación:

* **OAuth2:**
    * FOSOAuthServerBundle

                "jms/serializer-bundle": "^1.1",
                "friendsofsymfony/rest-bundle": "^1.7",
                "friendsofsymfony/user-bundle": "^1.3",
                "friendsofsymfony/oauth-server-bundle": "1.4.*@dev",
                "nelmio/api-doc-bundle": "^2.11"

* **JWT** 
    * LexicJWTAuthenticationBundle

                "jms/serializer-bundle": "^1.1",
                "lexik/jwt-authentication-bundle": "^1.3",
                "gesdinet/jwt-refresh-token-bundle": "^0.1.4",
                "friendsofsymfony/rest-bundle": "^1.7",
                "friendsofsymfony/user-bundle": "^1.3",
                "nelmio/api-doc-bundle": "^2.11"

## Site 1 

Instalar Symfony 2.8.* standard edition

    composer create-project symfony/framework-standard-edition practica "2.8.*"



## Site 2

No tiene porque ser symfony, podría servir cualquier aplicación que consuma el API, AngularJS, Backbone, Ember, etc...

