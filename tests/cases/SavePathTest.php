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
class SaveTestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testea que el handler sea del tipo files
     *
     * @return void
     */
    public function testSessionHandlerIsFile()
    {
        $handler = ini_get('session.save_handler');
        $this->assertEquals("files", $handler);
    }
    /**
     * Testea customizar el path donde se almacenan las sesiones
     *
     * @depends testSessionHandlerIsFile
     * @return void
     */
    public function testSetPath()
    {
        $path = "/tmp";
        \sesy\Session::savePath($path);
        $pathInit = ini_get('session.save_path');
        $pathSesy = \sesy\Session::savePath();

        $this->assertEquals($path, $pathInit);
        $this->assertEquals($path, $pathSesy);
    }
}
