<?php
/**
 * SessionSecure
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
 * SessionSecure
 *
 * Clase que aplica seguridad en las sesiones de php para previnir los ataques del tipo:
 * - Session Hijacking
 * - Session Fixation
 * - Cross-Site Session Transfer
 *
 * @category   Sesy
 * @package    Sesy
 * @subpackage Sesy
 * @author     Federico Lozada Mosto <mostofreddy@gmail.com>
 * @copyright  2013 Federico Lozada Mosto <mostofreddy@gmail.com>
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link       http://www.mostofreddy.com.ar
 */
class SessionSecure
{
    /**
     * Aplica seguridad en las sesiones.
     *
     * Se puede configurar algunos valores pasando un array de configuración
     *
     * $config = [
     *       'name' => 'sesySessionName', //nombre de sesion
     *       'cookie.path' => '/',        //path de la cookie
     *       'cookie.domain' => '',       //dominio de la cookie
     *       'expire' => (60*20),         //tiempo de vida de la sesion (en segundos)
     *       'https' => false,            //seguridad para cuando se utiliza https
     *       'storage' => null            //donde se almacenan las sesiones
     *   ];
     *
     * @param array $config config
     *
     * @access public
     * @static
     * @return void
     */
    public static function secure(array $config=array())
    {
        $defaults = [
            'name' => 'sesySessionName',
            'cookie.path' => '/',
            'cookie.domain' => '',
            'expire' => (60*20), //20 minutos
            'https' => false,
            'storage' => null
        ];
        $config = $config + $defaults;
        $sesConfig = new \sesy\SessionConfig();
        $sesConfig->name($config['name'])
            ->hash(1)
            ->cookies(true, true, $config['cookie.path'], $config['cookie.domain'], $config['https'])
            ->expire($config['expire'])
            ->cache('nocache')
            ->transid();
    }
}
