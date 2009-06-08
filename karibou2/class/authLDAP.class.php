<?php
/**
 * @copyright 2008 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package framework
 **/

/**
 * Used to check log-in of an user on a LDAP directory
 *
 * @package framework
 */

class AuthLDAP extends Auth
{
	function __construct ($params = null) {
		$this->params = $params;
	}
	
	/**
	 * @param String $user
	 * @param String $pass
	*/
	function check($user, $pass)
	{
		if (strlen($pass) == 0) {
			return false;
		}
		$ldapconn = @ldap_connect($this->params["server"]);
		$ldaprdn = "CN=" . $user . "," . $this->params["baseDN"];
		if ($ldapconn) {
			$ldapbind = @ldap_bind($ldapconn, $ldaprdn, stripslashes($pass));
			// verify binding
			if ($ldapbind) {
				@ldap_close($ldapconn);
				return true;
			}
			@ldap_close($ldapconn);
		}
		return false;
	}
	
}

?>
