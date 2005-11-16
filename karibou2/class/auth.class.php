<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/


/**
 * Authentication abstraction class
 * 
 * @package framework
 */ 
abstract class Auth
{
	/**
	 * @param String $user
	 * @param String $pass
	 */
	abstract function login($user, $pass) ;	
}
 

?>
