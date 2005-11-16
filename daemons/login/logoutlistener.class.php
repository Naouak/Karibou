<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information (GPL).
 * 
 * @package daemons
 **/

/**
 * @package daemons
 */
class LogoutListener extends Listener
{
	function eventOccured(Event $event)
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$currentUser->savePrefs($this->db);
		session_destroy();
		session_start();
	}
}

?>
