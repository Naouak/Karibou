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
		if (simple_krb5_chpass($user, $old_password, $new_password)) {
			return true;
		} else {
			// Ok, sometimes, simple_krb5_chpass will return false while the new password will be set and all right...
			// so, instead, we check if the new password is working.
			return simple_krb5_auth($user, $new_password);
		}
	}
}

?>
