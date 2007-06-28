<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

/**
 * Used to check Log-in of a user
 * 
 * @package applications
 */

class AuthImap extends Auth
{
	/**
	 * @param String $user
	 * @param String $pass
	 */
	function login($user, $pass)
	{
		$pass = stripslashes($pass);
		if( isset($GLOBALS['config']['login']['server'], 
			$GLOBALS['config']['login']['server_schema'], 
			$GLOBALS['config']['login']['server_options']) )
		{
			$server = $GLOBALS['config']['login']['server'];
			$folder = $GLOBALS['config']['login']['server_schema'];
			$options = $GLOBALS['config']['login']['server_options'];
			
			if( $imap = @imap_open("{".$server.$options."}".$folder, $user, $pass, OP_HALFOPEN) )
			{
				imap_close($imap);
				return true;
			}
		}
		else
		{
			Debug::kill("Missing config parameter for IMAP Login Backend");
		}
		return false;
	}

}

?>
