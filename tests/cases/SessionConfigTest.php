<?php
/**
 * SessionTest
 *
 * PHP version 5.4
 *
 * Copyright (c) 2013 mostofreddy <mostofreddy@gmail.com>
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @category   Test
 * @package    Sesy
 * @subpackage Tests\Cases
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
namespace sesy\tests\cases;
/**
 * Test unitario para testear el seteo del path donde se almacenan las sesiones en filesystem
 *
 * @category   Test
 * @package    Sesy
 * @subpackage Tests\Cases
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
class SessionConfigTest extends \PHPUnit_Framework_TestCase
{
    protected static $config;

    /**
     * Setea parametros al inicializar el test.
     * En este caso instancia el objeto SessionConfig para ser reutilizado en cada test
     *
     * @access public
     * @static
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        static::$config = new \sesy\SessionConfig();
    }

    /**
     * Testea que el metodo name lance una excepcion
     *
     * @access public
     * @return void
     */
    public function testNameFail()
    {
        $class = static::$config;
        try {
            static::$config->name(".....");
        } catch (\Exception $e) {
            $this->assertEquals($class::ERR_INVALID_SESSION_NAME, $e->getMessage());
        }
        try {
            static::$config->name("abc.123");
        } catch (\Exception $e) {
            $this->assertEquals($class::ERR_INVALID_SESSION_NAME, $e->getMessage());
        }
        try {
            static::$config->name("abc###");
        } catch (\Exception $e) {
            $this->assertEquals($class::ERR_INVALID_SESSION_NAME, $e->getMessage());
        }
    }

    /**
     * Testea el metodo name de la clase \sesy\SessionConfig
     *
     * @access public
     * @return void
     */
    public function testNameOk()
    {
        static::$config->name("misesion");
        $this->assertEquals("misesion", ini_get("session.name"));

        static::$config->name("misesion123");
        $this->assertEquals("misesion123", ini_get("session.name"));

        static::$config->name("123");
        $this->assertEquals("123", ini_get("session.name"));
    }

    /**
     * Testea el metodo gc de la clase \sesy\SessionConfig
     *
     * @access public
     * @return void
     */
    public function testGc()
    {
        static::$config->gc(20, 1000);
        $this->assertEquals(20, ini_get("session.gc_probability"));
        $this->assertEquals(1000, ini_get("session.gc_divisor"));
    }

    /**
     * Testea que el metodo hash de la clase \sesy\SessionConfig lance una excepcion
     *
     * @access public
     * @return void
     */
    public function testHashFail()
    {
        try {
            static::$config->hash(3);
        } catch (\Exception $e) {
            $class = static::$config;
            $this->assertEquals($class::ERR_INVALID_SESSION_HASH, $e->getMessage());
        }
    }
    /**
     * Testea el metodo hash de la clase \sesy\SessionConfig
     *
     * @access public
     * @return void
     */
    public function testHash()
    {
        static::$config->hash(1);
        $this->assertEquals(1, ini_get("session.hash_function"));
    }

    /**
     * Testea el metodo cookies de la clase \sesy\SessionConfig
     *
     * @access public
     * @return void
     */
    public function testCookies()
    {
        static::$config->cookies(false, false, '/dominio', 'dominio', true);
        $this->assertEquals(false, ini_get("session.use_only_cookies"));
        $this->assertEquals(false, ini_get("session.use_only_cookies"));
        $this->assertEquals('/dominio', ini_get("session.cookie_path"));
        $this->assertEquals('dominio', ini_get("session.cookie_domain"));
        $this->assertEquals(true, ini_get("session.cookie_secure"));
    }

    /**
     * Testea el metodo expire de la clase \sesy\SessionConfig
     *
     * @access public
     * @return void
     */
    public function testExpire()
    {
        static::$config->expire(50);
        $this->assertEquals(50, ini_get("session.gc_maxlifetime"));
        $this->assertEquals(50, ini_get("session.cookie_lifetime"));
    }

    /**
     * Testea pasar un directorio invalido a la funcion pathToSave de la clase \sesy\SessionConfig y lance una excepcion
     *
     * @access public
     * @return void
     */
    public function testPathToSaveFail()
    {
        $path = realpath(__DIR__."/../")."/invalidFolder";
        try {
            static::$config->pathToSave($path);
        } catch (\Exception $e) {
            $class = static::$config;
            $this->assertEquals(
                sprintf($class::ERR_INVALID_SESSION_PATH, $path),
                $e->getMessage()
            );
        }
    }
    /**
     * Testea la funcion pathToSave de la clase \sesy\SessionConfig
     *
     * @access public
     * @return void
     */
    public function testPathToSave()
    {
        $path = realpath(__DIR__."/../")."/";
        static::$config->pathToSave($path);
        $this->assertEquals($path, ini_get("session.save_path"));
    }
    /**
     * Testea configurar las sesiones con mmc
     *
     * @access  public
     * @return void
     */
    public function testEnableMMC()
    {
        static::$config->enableMmc();
        $this->assertEquals('memcache', ini_get("session.save_handler"));
        $this->assertEquals('tcp://localhost:11211', ini_get("session.save_path"));
    }
    /**
     * testea el metodo cache de la clase \sesy\SessionConfig
     *
     * @access public
     * @return void
     */
    public function testCacheFail()
    {
        $opciones = ['nocache', 'private', 'private_no_expire', 'public'];
        try {
            static::$config->cache("alguncache");
        } catch (\Exception $e) {
            $class = static::$config;
            $this->assertEquals(
                sprintf($class::ERR_INVALID_SESSION_CACHE, implode(', ', $opciones)),
                $e->getMessage()
            );
        }
    }
    /**
     * testea el metodo cache de la clase \sesy\SessionConfig
     *
     * @access public
     * @return void
     */
    public function testCache()
    {
        static::$config->cache("nocache");
        $this->assertEquals('nocache', ini_get("session.cache_limiter"));
    }
    /**
     * testea el metodo transid de la clase \sesy\SessionConfig
     *
     * @access public
     * @return void
     */
    public function testTransID()
    {
        static::$config->transid();
        $this->assertEquals(false, ini_get("session.use_trans_sid"));
        static::$config->transid(true);
        $this->assertEquals(true, ini_get("session.use_trans_sid"));
    }
}
