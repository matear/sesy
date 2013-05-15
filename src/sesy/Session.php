<?php
/**
 * Session
 *
 * PHP version 5.3
 *
 * Copyright (c) 2012 mostofreddy <mostofreddy@gmail.com>
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
 * Session
 *
 * Clase para manejar las variables de sesion
 *
 * @category   Sesy
 * @package    Sesy
 * @subpackage Sesy
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
class Session
{
    const ERR_INVALID_SESSION_NAME = 'Invalid session name. The session name can only contain [a-zA-Z0-9] values';

    const ERR_SESY_VIOLATED = "Invalid session";
    const ERR_INVALID_KEY = 'Invalid key for session value';
    const ERR_INVALID_SESSION_SAVE_PATH = "Invalid session.save_path. (is empty or is not writable)";

    /**
     * Indica si la sesion fue inicializada o no
     * @var bool
     */
    static protected $started = false;
    static protected $token = 'SesySessionToken';
    static protected $init = array(
        //especifica el nombre de la sesión que se usa como nombre de cookie. Sólo debería contener caracteres alfanuméricos
        'session.name' => 'sesy',  //-

        //especifica el número de segudos transcurridos después de que la información sea vista como 'basura' y potencialmente limpiada.
        //La recolección de basura puede suceder durante el inicio de sesiones
        'session.gc_maxlifetime'=>300, //-
        //junto con session.gc_probability define la probabilidad de que el proceso de gc (garbage collection, recolección de basura)
        //esté iniciado en cada inicialización de sesión. La probabilidad se calcula usando gc_probability/gc_divisor,
        //p.ej. 1/100 significa que hay un 1% de probabilidad de que el proceso de GC se inicie en cada petición
        'session.gc_divisor'=>100, //-
        //se usa junto con session.gc_divisor para manejar la probabilidad de que la rutina de gc
        //(garbage collection, recolección de basura) está iniciada
        'session.gc_probability'=>50, //-

        //permite especificar el algoritmo hash utilizado para generar los ID de sesión.
        //'0' significa MD5 (128 bits) y '1' significa SHA-1 (160 bits).
        'session.hash_function'=>1,
        //no usa cache
        'session.cache_limiter'=>'nocache',
        //specifica el tiempo de vida en minutos para las páginas de sesión examinadas, esto no tiene efecto para el limitador nocache
        'session.cache_expire'=>1,
        //debe estar en 0 por seguridad para no propagar el SID de la session por url
        'session.use_trans_sid' => 0,

        //especifica el tiempo de vida en segundos de la cookie que es enviada al navegador.
        //El valor 0 significa "hasta que el navegador se cierre"
        //La marca de tiempo de caducidad se establece relativa a la hora del servidor,
        //la cúal no es necesariamente la misma que la hora del navegador del cliente.
        'session.cookie_lifetime'=> 0, //-
        //Marca la cookie como accesible sólo a través del protocolo HTTP.
        //Esto siginifica que la cookie no será accesible por lenguajes de script, tales como JavaScript.
        //Habilitar este ajuste previene ataques que impican pasar el id de sesión en la URL Previene Cross-Site Scripting
        'session.cookie_httponly'=>1, //-
        //especifica si el módulo sólo usará cookies para almacenar el id de sesión en la parte del cliente
        //Previene Session Fixation
        'session.use_only_cookies'=>1, //-

        //define el nombre del gestor que se usa para almacenar y recuperar información asociada con una sesión
        'session.save_handler'=> 'files',  //-
        //define el argumento que es pasado al gestor de almacenamiento.
        //Si se elige el gestor de archivos por defecto, éste es la ruta donde los archivos son creados
        //Previene Cross-Site Session Transfer
        'session.save_path'=>'/tmp' //-
    );
    /**
     * Setea el path en donde se almacenaran las sesiones
     *
     * @param string $path path
     *
     * @return self
     */
    static public function savePath($path)
    {
        if (!is_string($path)
            || ($path != ''
            && (!is_dir($path) || !is_writable($path)))
        ) {
            throw new \InvalidArgumentException(static::ERR_INVALID_SESSION_SAVE_PATH);
        }
        static::$init['session.save_path'] = $path;
        static::$init['session.save_handler'] = 'files';
    }
    /**
     * Nombre de la sesion actual
     *
     * @param string $name name
     *
     * @return self
     */
    static public function name($name)
    {
        if (preg_match('/[^a-z0-9]/i', $name) > 0) {
            throw new \InvalidArgumentException(static::ERR_INVALID_SESSION_NAME);
        }
        static::$init['session.name'] = $name;
        static::$token = $name;
    }
    /**
     * Sete la configuracion de las cookies en session
     *
     * @param integer $lifeTime       especifica el tiempo de vida en segundos de la cookie que es enviada al navegador.
     * @param integer $httpOnly       Marca la cookie como accesible sólo a través del protocolo HTTP.
     * @param integer $useOnlyCookies especifica si el módulo sólo usará cookies para almacenar el id de sesión en la parte del cliente
     *
     * @return void
     */
    static public function cookies($lifeTime = 0, $httpOnly = 1, $useOnlyCookies = 1)
    {
        static::$init['session.cookie_lifetime'] = $lifeTime;
        static::$init['session.cookie_httponly'] = $httpOnly;
        static::$init['session.use_only_cookies'] = $useOnlyCookies;
    }
    /**
     * Define el funcionamiento del garbage collector de sessiones
     *
     * @param int $maxLife     especifica el número de segudos transcurridos después de que la información sea vista como 'basura'
     *                         y potencialmente limpiada.
     * @param int $divisor     junto con session.gc_probability define la probabilidad de que el proceso de gc sea ejecutado
     * @param int $probability se usa junto con session.gc_divisor para manejar la probabilidad de que la rutina de gc
     *
     * @return void
     */
    static public function gc($maxLife = 300, $divisor = 100, $probability = 50)
    {
        static::$init['session.gc_maxlifetime'] = $maxLife;
        static::$init['session.gc_divisor'] = $divisor;
        static::$init['session.gc_probability'] = $probability;
    }
    /**
     * Setea el tipo de algoritmo que utiliza para generar los ID de sesión
     * '0' significa MD5 (128 bits) y '1' significa SHA-1 (160 bits).
     * también es posible especificar cualquier algoritmo porporcionado por la extensión hash (si esta disponible)
     *
     * @param integer|string $hash tipo de algoritmo
     *
     * @return void
     */
    static public function hash($hash = 1)
    {
        static::$init['session.hash_function'] = $hash;
    }
    /**
     * Setea en cuanto tiempo expira una sesion (en segundos)
     *
     * @param int $seg tiempo de expiracion en segundos
     *
     * @return self
     */
    static public function expire($seg)
    {
        static::$init['session.cookie_lifetime'] = $seg;
        static::$init['session.gc_maxlifetime'] = $seg;
    }
    /**
     * Inicializa la sesion de PHP
     *
     * @return void
     */
    static public function start()
    {
        if (!static::$started) {
            static::_initSet();
            session_start();
            /* Previene Session Fixation */
            static::_regenerateId();
            static::_valid();
            static::$started = true;
        }
        return true;
    }
    /**
     * Agrega un valor en sesion
     *
     * @param string $sKey  nombre de la clave
     * @param mixed  $value valor a almacenar
     *
     * @return void
     */
    static public function add($sKey, $value)
    {
        if (!is_string($sKey)) {
            throw new \InvalidArgumentException(static::ERR_INVALID_KEY);
        }
        $_SESSION[$sKey] = $value;
        return true;
    }
    /**
     * Borra un valor en sesion
     *
     * @param string $sKey nombre de la clave
     *
     * @return void
     */
    static public function delete($sKey)
    {
        if (!is_string($sKey)) {
            throw new \InvalidArgumentException(static::ERR_INVALID_KEY);
        }
        if (isset($_SESSION[$sKey])) {
            unset($_SESSION[$sKey]);
        }
        return true;
    }
    /**
     * Recupera un valor de sesion
     * Puede definirse un valor por default en caso de que no encuentre la clave, por default devuelve null
     *
     * @param string $sKey    nombre de la clave
     * @param mixed  $default valor por default si no encuentra el dato (default: null)
     *
     * @return mixed
     */
    static public function get($sKey=null, $default=null)
    {
        if ($sKey === null) {
            return $_SESSION;
        }
        return (array_key_exists($sKey, $_SESSION))?$_SESSION[$sKey]:$default;
    }
    /**
     * Finaliza/Cierra una sesion
     *
     * @return bool
     */
    static public function close()
    {
        $_SESSION = array();
        unset($_SESSION);
        return session_destroy();
    }
    /**
     * Setea todos los parametros de configuracion en php
     *
     * @return void
     */
    static private function _initSet()
    {
        foreach (static::$init as $k => $v) {
            ini_set($k, $v);
        }
        session_set_cookie_params(static::$init['session.cookie_lifetime']);
    }

    /**
     * Actualiza el id de sesion
     *
     * @return bool
     */
    static private function _regenerateId()
    {
        return session_regenerate_id(true);
    }
    /**
     * Valida que la sesion actual sea valida, de lo contrario lanza una exepcion del tipo \RuntimeException
     *
     * @return bool
     */
    static private function _valid()
    {
        $token = static::_generateToken();
        if (isset($_SESSION['SesySessionToken'])) {
            if ($_SESSION['SesySessionToken'] !== $token) {
                static::close();
                throw new \RuntimeException(static::ERR_SESY_VIOLATED);
            }
        } else {
            static::add('SesySessionToken', $token);
        }
        return true;
    }
    /**
     * Genera un token que luego permitira validar una sesion
     *
     * @return string
     */
    static private function _generateToken()
    {
        $token = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
        $token .= static::$token;
        return sha1(md5($token));
    }
}
