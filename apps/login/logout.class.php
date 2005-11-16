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
 * Used to Log-out a user
 * 
 * @package applications
 */

class Logout extends FormModel
{
	function build()
	{
		$this->eventManager->sendEvent("logout");
	}
}

?>
