<?php
/**
 * SessionConfig
 *
 * PHP version 5.4
 *
 * Copyright (c) 2013 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category   Sesy
 * @package    Sesy
 * @subpackage Sesy
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
namespace sesy;
/**
 * Clase para configurar las sesiones de php
 *
 * Esta clase permite setear la configuración de las sesiones en tiempo de ejecución cuando no se tiene acceso a modificar directamete
 * las directivas en el archivo php.ini
 *
 * Se recomienda que si se tiene acceso al archivo php.ini, esta configuración se haga directamente alli.
 *
 * Esta clase permite configurar las siguientes directivas:
 *
 * Nombre de session:
 *     session.name:
 *         especifica el nombre de la sesión que se usa como nombre de cookie. Sólo debería contener caracteres alfanuméricos
 *
 * Garbage collector
 *     session.gc_maxlifetime:
 *         especifica el número de segudos transcurridos después de que la información sea vista como 'basura' y potencialmente limpiada.
 *         La recolección de basura puede suceder durante el inicio de sesiones
 *     session.gc_divisor:
 *         junto con session.gc_probability define la probabilidad de que el proceso de gc (garbage collection, recolección de basura)
 *         esté iniciado en cada inicialización de sesión. La probabilidad se calcula usando gc_probability/gc_divisor,
 *         p.ej. 1/100 significa que hay un 1% de probabilidad de que el proceso de GC se inicie en cada petición
 *     session.gc_probability:
 *         se usa junto con session.gc_divisor para manejar la probabilidad de que la rutina de gc
 *
 * Definir el algoritmo que utiliza php para generar los IDs de sesion
 *     session.hash_function:
 *         '0' significa MD5 (128 bits) y '1' significa SHA-1 (160 bits).
 *
 * Configuración de Cookies
 *     session.use_only_cookies:
 *         especifica si el módulo sólo usará cookies para almacenar el id de sesión en la parte del cliente
 *         Previene Session Fixation cuando su valor es true|1
 *     session.cookie_httponly;
 *         Marca la cookie como accesible sólo a través del protocolo HTTP.
 *         Esto siginifica que la cookie no será accesible por lenguajes de script, tales como JavaScript.
 *         Habilitar este ajuste previene ataques que impican pasar el id de sesión en la URL Previene Cross-Site Scripting
 *     session.cookie_lifetime:
 *          La marca de tiempo de caducidad se establece relativa a la hora del servidor,
 *          la cúal no es necesariamente la misma que la hora del navegador del cliente.
 *
 * Tiempo de vida de una sesion
 *     session.gc_maxlifetime:
 *         especifica el número de segudos transcurridos después de que la información sea vista como 'basura' y potencialmente limpiada.
 *         La recolección de basura puede suceder durante el inicio de sesiones
 *     session.cookie_lifetime:
 *         especifica el tiempo de vida en segundos de la cookie que es enviada al navegador
 *         El valor 0 significa "hasta que el navegador se cierre"
 *
 * Path donde se guardar los archivos
 *
 * Habilitar el handler nativo de de Memcache
 *
 * Cache
 *     session.cache_limiter: (nocache, private, private_no_expire, public)
 *         especifica el método de control de caché usado por páginas de sesión
 *     session.cache_expire
 *         especifica el tiempo de vida en minutos para las páginas de sesión examinadas, esto no tiene efecto para el limitador nocache
 *
 * Otros
 *     session.use_trans_sid:
 *         debe estar en 0 por seguridad para no propagar el SID de la session por url
 *
 * @category   Sesy
 * @package    Sesy
 * @subpackage Sesy
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
class SessionConfig
{
    const ERR_INVALID_SESSION_NAME = 'Nombre de sesion inválido. Debe ser una cadena alfanumérica';
    const ERR_INVALID_SESSION_HASH = 'Se definio mal el algoritmo para generar el hash. Los valores posibles son: 0 (MD5) y 1 (SHA-1)';
    const ERR_INVALID_SESSION_PATH = 'El path "%s" no es un directorio válido o no tiene permisos de escritura/lectura';
    const ERR_INVALID_SESSION_CACHE = 'Opción inválida para el cache de sesion, las opciones permitidas son: %s';
    /**
     * Nombre de la sesion actual
     *
     * @param string $name name
     *
     * @return \sesy\SessionConfig
     */
    public function name($name)
    {
        if (preg_match('/[^a-z0-9]/i', $name) > 0) {
            throw new \InvalidArgumentException(static::ERR_INVALID_SESSION_NAME);
        }
        ini_set('session.name', $name);
        return $this;
    }
    /**
     * Define el funcionamiento del garbage collector de sessiones
     *
     * @param int $probability se usa junto con session.gc_divisor para manejar la probabilidad de que la rutina de gc
     * @param int $divisor     junto con session.gc_probability define la probabilidad de que el proceso de gc sea ejecutado
     *
     * @return \sesy\SessionConfig
     */
    public function gc($probability = 50, $divisor = 100)
    {
        ini_set('session.gc_divisor', (int) $divisor);
        ini_set('session.gc_probability', (int) $probability);
        return $this;
    }
    /**
     * Setea el tipo de algoritmo que utiliza para generar los ID de sesión
     * '0' significa MD5 (128 bits) y '1' significa SHA-1 (160 bits).
     * también es posible especificar cualquier algoritmo porporcionado por la extensión hash (si esta disponible)
     *
     * @param integer|string $hash tipo de algoritmo
     *
     * @return \sesy\SessionConfig
     */
    public function hash($hash = 1)
    {
        $hash = (int) $hash;
        if (!in_array($hash, [0, 1])) {
            throw new \InvalidArgumentException(static::ERR_INVALID_SESSION_HASH);
        }
        ini_set('session.hash_function', $hash);
        return $this;
    }
    /**
     * Sete la configuracion de las cookies en session
     *
     * @param bool   $httpOnly       Marca la cookie como accesible sólo a través del protocolo HTTP. (Default: true)
     * @param bool   $useOnlyCookies Especifica si el módulo sólo usará cookies para almacenar el id de sesión en
     *                               la parte del cliente (Default: true)
     * @param string $path           Especifica la ruta a establecer en la cookie de sesión. (Default: /)
     * @param string $domain         Especifica el dominio a establecer en la cookie de sesión. (Default: '')
     * @param bool   $secure         Especifica si las cookies deberían enviarse sólo sobre conexiones seguras. (Default: false)
     *
     * @return \sesy\SessionConfig
     */
    public function cookies($httpOnly = true, $useOnlyCookies = true, $path='/', $domain='', $secure=false)
    {
        ini_set('session.cookie_httponly', $httpOnly);
        ini_set('session.use_only_cookies', $useOnlyCookies);
        ini_set('session.cookie_path', $path);
        ini_set('session.cookie_domain', $domain);
        ini_set('session.cookie_secure', $secure);
        return $this;
    }
    /**
     * Setea en cuanto tiempo expira una sesion (en segundos)
     *
     * @param int $seg tiempo de expiracion en segundos
     *
     * @return \sesy\SessionConfig
     */
    public function expire($seg)
    {
        ini_set('session.gc_maxlifetime', (int) $seg);
        ini_set('session.cookie_lifetime', (int) $seg);
        return $this;
    }
    /**
     * Setea el directorio donde se almacenaran las sesiones
     *
     * @param string $path path del directorio
     *
     * @return \sesy\SessionConfig
     */
    public function pathToSave($path)
    {
        $path = escapeshellcmd($path);
        if (!is_dir($path) || !is_writable($path)) {
            throw new \InvalidArgumentException(
                sprintf(static::ERR_INVALID_SESSION_PATH, $path)
            );
        }
        ini_set('session.save_handler', 'files');
        session_save_path($path);
        return $this;
    }
    /**
     * Habilita el handler nativo de de Memcache
     *
     * @param string $host host del mmc (Default: localhost)
     * @param int    $port puerto de mmc (Default: 11211)
     *
     * @return \sesy\SessionConfig
     */
    public function enableMmc($host='localhost', $port=11211)
    {
        $port = (int) $port;
        ini_set('session.save_handler', 'memcache');
        session_save_path("tcp://".$host.":".$port);
        return $this;
    }

    /**
     * Setea la privacidad del cache
     *
     * @param string $cache tipo de cache
     *
     * @access public
     * @return mixed Value.
     */
    public function cache($cache)
    {
        $opciones = ['nocache', 'private', 'private_no_expire', 'public'];
        if (!in_array($cache, $opciones)) {
            throw new \InvalidArgumentException(
                sprintf(static::ERR_INVALID_SESSION_CACHE, implode(', ', $opciones))
            );
        }
        ini_set('session.cache_limiter', $cache);
        return $this;
    }

    /**
     * Si está habilitado sid transparente o no
     *
     * @param bool $trans booleano que habilita o no. (Default: false)
     *
     * @access public
     * @return mixed Value.
     */
    public function transid($trans=false)
    {
        ini_set('session.use_trans_sid', $trans);
        return $this;
    }
}
