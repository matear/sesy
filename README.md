__Sesy__
========

[![Build Status](https://travis-ci.org/mostofreddy/sesy.png?branch=master)](https://travis-ci.org/mostofreddy/sesy)

Librería POO para agregar seguridad en las sesiones de PHP previniendo distintos tipos de ataques.

Que tipos de ataque previene
----------------------------

Por el momento Sesy previene de 3 tipos de ataques:

* __Session Hijacking__
Este tipo de ataque se caracteriza por el robo del identificador de sesion entre una página web y un usuario
de esta forma el atacante puede hacerse pasar por la victima.

* __Session Fixation__
Es un tipo de robo de sesion un poco especial porque en el se intenta asignar un identificado de sesion
conocido a un usuario antes de que se autentifique.

* __Cross-Site Session Transfer__
Es un tipo de ataque que solo puede darse en servidores compartidos

### Como lo previene Sesy

* utiliza session.use_only_cookies con el valor 1
* usa un directorio local para las sesiones en servidores compartidos
* utiliza session.cookie_httponly con valor 1
* limita el tiempo de vida de una sesion
* cambia el identificador de sesion y borrar la anterior en cada request
* permite destruir una sesion totalmente
* utiliza verificación doble agregando un token en sesion y validando en cada request que sea válido

### Almacenamiento customizable de las sessiones

* Seteo de carpeta donde se almacenaran las sessiones
* Posibilidad de almacenar las sessiones en base de datos (próximamente)

Version
-------

- __v0.5__ stable
- __v1.0__ desarrollo

Licencia
-------

[MIT License](http://www.opensource.org/licenses/mit-license.php)

Instalación
-----------

### dependencias

- PHP 5.4.*

### Github

    cd /var/www
    git clone git@github.com:mostofreddy/sesy.git

### Composer

    "require": {
        "php": ">=5.4.0",
        "mostofreddy/sesy": "0.*",
    }

Ejemplo de uso
--------------

    // se setea un token para que el nombre de la session no sea la default de PHP
    \sesy\Session::name("token");

    // se setea donde se almacenaran los archivos de las sesiones de PHP
    \sesy\Session::savePath(realpath(dirname(__FILE__)."/tmp"));

    //seteo de tiempo de vida de las sessiones en segundos
    \sesy\Session::expire(60);

    // se inicializa la session
    \sesy\Session::start();

    if (isset($_POST['username'])) {
        \sesy\Session::add("username", $_POST['username']);
    }

Demos
-----

Visite la carpeta de [demos](https://github.com/mostofreddy/sesy/tree/master/demos) para mas ejemplos

Changelog
---------

### v1.0 (en desarrollo)

- Fixed bug [#5](https://github.com/mostofreddy/sesy/issues/5)
- Se elimina compatibilidad con PHP 5.3.x

### v0.5
