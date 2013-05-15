<?php
/**
 * Demo basiso para guardar las sessiones en un directorio customizado
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
 * @copyright  2012 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 * @since      v0.5
 */
require_once "../src/sesy/Session.php";

// se setea un token para que el nombre de la session no sea la default de PHP
\sesy\Session::name("token");

// se setea donde se almacenaran los archivos de las sesiones de PHP
\sesy\Session::savePath(realpath(dirname(__FILE__)."/tmp"));

\sesy\Session::cookies();
\sesy\Session::gc();
\sesy\Session::hash();

//seteo de tiempo de vida de las sessiones en segundos
\sesy\Session::expire(60);

// se inicializa la session
\sesy\Session::start();

if (isset($_POST['username'])) {
    \sesy\Session::add("username", $_POST['username']);
}
?>
Username almacenado: <?php echo \sesy\Session::get("username", 'Ninguno')?><br/>
<form action="" method="post">
    <input type="text" name="username" value="" placeholder="Valor a almacenar en session"
    style="width:300px;"/>
    <input type="submit" value="Guardar"/>
</form>
