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
 * Used to check log-in of an user using Kerberos5 through the simple_krb5 library
 *
 * @package framework
 */

class AuthKRB5 extends Auth
{
	/**
	 * @param String $user
	 * @param String $pass
	*/
	function check($user, $pass)
	{
		return simple_krb5_auth($user, $pass);
	}
	
	/**
	 * @param String $user
	 * @param String $old_password
	 * @param String $new_password
	 */
	function change ($user, $old_password, $new_password)
	{
		return simple_krb5_chpass($user, $old_password, $new_password);
	}
}

?>
