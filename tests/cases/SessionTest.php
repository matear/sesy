<?php
/**
 * SessionTest
 *
 * PHP version 5.3
 *
 * Copyright (c) 2012 mostofreddy <mostofreddy@gmail.com>
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
 * Test unitario para la clase \sesy\Session
 *
 * @category   Test
 * @package    Sesy
 * @subpackage Tests\Cases
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
class SessionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testea el metodo savePath pasandole un parametro incorrecto
     *
     * @expectedException \InvalidArgumentException
     * @return void
     */
    public function testSavePathException()
    {
        \sesy\Session::savePath("/file");
    }
    /**
     * Testea el metodo savePath
     *
     * @return void
     */
    public function testSavePath()
    {
        \sesy\Session::savePath("/tmp");
        $aux = \PHPUnit_Framework_Assert::readAttribute("\sesy\Session", 'init');

        $this->assertEquals('files', $aux['session.save_handler']);
        $this->assertEquals('/tmp', $aux['session.save_path']);
    }
    /**
     * Testea el metodo name pasandole un valor incorrecto
     *
     * @expectedException \InvalidArgumentException
     * @return void
     */
    public function testNameException()
    {
        $expected = "sesyTests_1";
        \sesy\Session::name($expected);
    }
    /**
     * Testea el metodo name
     *
     * @return void
     */
    public function testName()
    {
        $expected = "sesyTests";
        \sesy\Session::name($expected);
        $aux1 = \PHPUnit_Framework_Assert::readAttribute("\sesy\Session", 'init');
        $aux2 = \PHPUnit_Framework_Assert::readAttribute("\sesy\Session", 'token');

        $this->assertEquals($expected, $aux1['session.name']);
        $this->assertEquals($expected, $aux2);
    }
    /**
     * Testea el metodo cookies
     *
     * @return void
     */
    public function testCookies()
    {
        $expected = array(
            'lifetime' => 400,
            'httponly' => 1,
            'useOnlyCookies' => 1
        );
        \sesy\Session::cookies($expected['lifetime'], $expected['httponly'], $expected['useOnlyCookies']);
        $aux = \PHPUnit_Framework_Assert::readAttribute("\sesy\Session", 'init');

        $this->assertEquals($expected['lifetime'], $aux['session.cookie_lifetime']);
        $this->assertEquals($expected['httponly'], $aux['session.cookie_httponly']);
        $this->assertEquals($expected['useOnlyCookies'], $aux['session.use_only_cookies']);
    }
    /**
     * Testea el metodo gc
     *
     * @return void
     */
    public function testGc()
    {
        $expected = array(
            'gc_maxlifetime' => 400,
            'gc_divisor' => 100,
            'gc_probability' => 50
        );
        \sesy\Session::gc($expected['gc_maxlifetime'], $expected['gc_divisor'], $expected['gc_probability']);
        $aux = \PHPUnit_Framework_Assert::readAttribute("\sesy\Session", 'init');

        $this->assertEquals($expected['gc_maxlifetime'], $aux['session.gc_maxlifetime']);
        $this->assertEquals($expected['gc_divisor'], $aux['session.gc_divisor']);
        $this->assertEquals($expected['gc_probability'], $aux['session.gc_probability']);
    }
    /**
     * Testea el metodo hash
     *
     * @return void
     */
    public function testHash()
    {
        $expected = 1;
        \sesy\Session::hash($expected);
        $aux = \PHPUnit_Framework_Assert::readAttribute("\sesy\Session", 'init');

        $this->assertEquals($expected, $aux['session.hash_function']);
    }
    /**
     * Testea el metodo exprire
     *
     * @return void
     */
    public function testExpire()
    {
        $expected = 300;
        \sesy\Session::expire($expected);
        $aux = \PHPUnit_Framework_Assert::readAttribute("\sesy\Session", 'init');

        $this->assertEquals($expected, $aux['session.cookie_lifetime']);
        $this->assertEquals($expected, $aux['session.gc_maxlifetime']);
    }
    /**
     * Testea el metodo start
     *
     * @return void
     */
    public function testStart()
    {
        \sesy\Session::start();
        $aux = \PHPUnit_Framework_Assert::readAttribute("\sesy\Session", 'started');
        $this->assertTrue($aux);
    }
    /**
     * Testea los metodos add/get
     *
     * @return void
     */
    public function testAddGet()
    {
        \sesy\Session::add("name", "sesy");
        $aux = \sesy\Session::get("name");
        $this->assertEquals("sesy", $aux);
    }
    /**
     * Testea el metodo add pasandole un parametro incorrecto como key
     *
     * @expectedException \InvalidArgumentException
     * @return void
     */
    public function testAddException()
    {
        \sesy\Session::add(new \StdClass(), "sesy");
    }
    /**
     * Testea los metodos add/get
     *
     * @return void
     */
    public function testAddGetAll()
    {
        \sesy\Session::add("name", "sesy");
        $aux = \sesy\Session::get();
        $this->assertEquals(array("name" => "sesy"), $aux);
    }
}
