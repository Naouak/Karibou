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
 * Used to check log-in of an user using PAM
 *
 * @package framework
 */

class AuthPAM extends Auth
{
	/**
	 * @param String $user
	 * @param String $pass
	*/
	function check($user, $pass)
	{
		return pam_auth($user, $pass);
	}
	
	/**
	 * @param String $user
	 * @param String $old_password
	 * @param String $new_password
	 */
	function change ($user, $old_password, $new_password)
	{
		return pam_chpass($user, $old_password, $new_password);
	}
}

?>
