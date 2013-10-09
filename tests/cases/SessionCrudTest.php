<?php
/**
 * SessionCrudTest
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
 * Clase de test unitario para testear los metodos de set, get, delete de variables en sesion
 *
 * @category   Test
 * @package    Sesy
 * @subpackage Tests\Cases
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
class SessionCrudTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testea el metodo set
     *
     * @access public
     *
     * @return void
     */
    public function testSetOk()
    {
        $obj = new \sesy\Session();
        $obj->start();
        $obj->set('key', 'value');
        $this->assertEquals($_SESSION['key'], 'value');
    }
    /**
     * dataProviderInvalidKey
     *
     * @access protected
     *
     * @return mixed Value.
     */
    public function dataProviderInvalidKey()
    {
        $keys = array(
            array(new \StdClass()),
            array(array()),
            array(1)
        );
        return $keys;
    }
    /**
     * Testea el metodo set pasando una clave incorrecta
     *
     * @param mixed $key invalid key
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid key. Expected a string
     * @dataProvider dataProviderInvalidKey
     * @access public
     * @return void
     */
    public function testSetNok($key)
    {
        $obj = new \sesy\Session();
        $obj->start();
        $obj->set($key, 'value');
    }


    /**
     * Testea el metodo get
     *
     * @depends testSetOk
     * @access public
     * @return mixed Value.
     */
    public function testGetOk()
    {
        $obj = new \sesy\Session();
        $obj->start();
        $obj->set('keyGet', 'value');
        $this->assertEquals($obj->get("keyGet"), 'value');
    }
    /**
     * Testea el metodo get que devuelva el valor por default por no encontrar la key
     *
     * @depends testGetOk
     * @access public
     * @return mixed Value.
     */
    public function testGetDefault()
    {
        $obj = new \sesy\Session();
        $obj->start();
        $this->assertEquals(
            $obj->get('keyGetNot', 'sameValue'),
            'sameValue'
        );
    }
    /**
     * Testea el metodo get que devuelva todos los valores que tiene almacenado en sesion
     *
     * @depends testGetOk
     * @access public
     * @return mixed Value.
     */
    public function testGetAll()
    {
        $obj = new \sesy\Session();
        $obj->start();
        $expected = array(
            "key1" => "value1",
            "key2" => "value2"
        );
        $_SESSION = $expected;
        $this->assertEquals(
            $obj->get(),
            $expected
        );
    }
    /**
     * Testea el metodo get lance excepciones cuando se le pasa una clave inválida
     *
     * @param mixed $key invalid key
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid key. Expected a string
     * @dataProvider dataProviderInvalidKey
     * @access public
     * @return mixed Value.
     */
    public function testGetNok($key)
    {
        $obj = new \sesy\Session();
        $obj->start();
        $obj->get($key);
    }

    /**
     * Testea el metodo get
     *
     * @depends testSetOk
     * @depends testGetOk
     * @access public
     * @return mixed Value.
     */
    public function testDeleteOk()
    {
        $obj = new \sesy\Session();
        $obj->start();
        $obj->set('keyGet', 'value');
        $this->assertEquals($obj->get("keyGet"), 'value');
        $obj->delete('keyGet');
        $this->assertEquals(
            $obj->get("keyGet", 'no existe'),
            'no existe'
        );
    }
    /**
     * Testea el metodo get lance una excepcion cuando se le pasa una clave invalida
     *
     * @param mixed $key invalid key
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid key. Expected a string
     * @dataProvider dataProviderInvalidKey
     * @access public
     * @return mixed Value.
     */
    public function testDeleteNok($key)
    {
        $obj = new \sesy\Session();
        $obj->start();
        $obj->delete($key);
    }
}
