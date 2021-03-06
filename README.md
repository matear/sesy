**Deprecado**

-- ---

Librería para el manejo, configuración y seguridad de sesiones en PHP.

Descripción
-----------

- version [html](http://mostofreddy.github.io/sesy/index.html)
- version [pdf](http://mostofreddy.github.io/sesy/download/sesy.pdf)

### Configuración

Permite setear la configuración de las sesiones en tiempo de ejecución cuando no se tiene acceso a modificar directamete las directivas
en el archivo php.ini.
Los valores que permite modificar son:

- Nombre de session
- Garbage collector
- Algoritmo que utiliza php para generar los IDs de sesion
- Cookies
- Tiempo de vida de una sesion
- Directorio donde se almacenan los archivos de sesion
- Habilitar Memcache para almacenar las sesiones
- Cache

NOTA: Se recomienda, por temas de rendimiento, que si tiene acceso al archivo php.ini modifique en él los datos y no utilice esta clase

### Wraper POO

La clase \sesy\Session es un wrapper POO para el manejo de las sesiones, con ella podemos desacoplar el uso de las variables de sesion de
nuestras clases/funciones mediante dependencias.

- guardar/recuperar/borrar valores en sesion
- inicializar una sesion (cequeando que no este inicializada anteriormente)
- cerrado de sesion, borrado de datos y archivos
- Setear el directorio donde se almacenan los archivos de sesion
- Configurar Memcache como storage de sesion

### Seguridad

La clase \sesy\SessionSecure configura de forma automatica las sesiones para que sean seguras previniendo ataques del tipo:

- Session Hijacking
- Session Fixation
- Cross-Site Session Transfer

API
---

Ver [API](http://mostofreddy.github.io/sesy/api/)

Versión
-------

- __v1.0.1__ stable
- __v1.1.0__ desarrollo

Licencia
-------

[MIT License](http://www.opensource.org/licenses/mit-license.php)

Instalación
-----------

### Requerimientos

- PHP 5.4.*
- [Memcached deamon](http://memcached.org) (opcional)
- [Módulo memcache](http://php.net/manual/es/book.memcache.php) (opcional)

### Github

    cd /var/www
    git clone git@github.com:mostofreddy/sesy.git

### Composer

    "require": {
        "php": ">=5.4.0",
        "mostofreddy/sesy": "1.0.*",
    }

Ejemplos base de uso
--------------------

### configuración

    $sesConfig = new \sesy\SessionConfig();
    $sesConfig->name("sesy")
        ->expire(60)
        .....
        ->gc();

### seguridad

Setea parametros de seguridad de forma automática.

    \sesy\SessionSecure::secure();

### Uso

#### Instanciación

    $ses = new \sesy\Session();
    $ses->start();

#### Guardar datos

    $ses->set("mykey1", "value");
    $ses->set("mykey2", new \StdClass);
    $ses->set("mykey3", array());

#### Recuperar datos

    $data1 = $ses->get("mykey1");

#### Recuperar todos los datos de session

    $allData = $ses->get();

#### Datos por default cuando no existe la clave en sesion

    $defaultData = $ses->get("invalidKey", "data not found");

Tests
-----

    phpunit --configuration tests/phpunit.xml

Demos
-----

Visite la carpeta de [demos](https://github.com/mostofreddy/sesy/tree/master/demos) para mas ejemplos

Changelog
---------

### v1.0.1

- Validaciones de tipo de dato los metodos set, get, delete
- Posibilidad de cambiar el nombre de sesion

### v1.0.0

- Fixed bug [#5](https://github.com/mostofreddy/sesy/issues/5)
- Se elimina compatibilidad con PHP 5.3.x
- Feature: Desacoplar lógica de configuración [#11](https://github.com/mostofreddy/sesy/issues/11)
- Feature: Implementar Handler: memcache [#1](https://github.com/mostofreddy/sesy/issues/1)
- Feature: Abstracción de seguridad [#12](https://github.com/mostofreddy/sesy/issues/12)

### v0.5
