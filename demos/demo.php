<?php
/**
 * Demo basiso para guardar las sessiones en un directorio customizado
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
require_once "../src/sesy/Session.php";
require_once "../src/sesy/SessionConfig.php";

$sesConfig = new \sesy\SessionConfig();
$sesConfig->name("sesy")
    ->expire(60)
    ->gc();

$ses = new \sesy\Session();
$ses->storeInFiles(__DIR__."/tmp");
$ses->start();

if (isset($_POST['username'])) {
    $ses->set('username', $_POST['username']);
}

var_dump($ses->get());
?>
<form action="" method="post">
    <input type="text" name="username" value="" placeholder="Valor a almacenar en session"
    style="width:300px;"/>
    <input type="submit" value="Guardar"/>
</form>

