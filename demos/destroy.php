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
$sesConfig->name("sesy");

$ses = new \sesy\Session();
$ses->storeInFiles(__DIR__."/tmp");
$ses->start()
    ->destroy();
header("Location: demo.php");
