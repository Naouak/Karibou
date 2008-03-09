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
	private $params;
	function __construct ($params = null) {
		$this->params = $params;
	}
	
	/**
	 * @param String $user
	 * @param String $pass
	 */
	abstract function check($user, $pass) ;	

	/**
	 * @param String $user
	 * @param String $old_password
	 * @param String $new_password
	 */
	function change ($user, $old_password, $new_password) {
		return false;
	}
	
	function notify ($user, $password) {
		return false;
	}
}
 

?>
