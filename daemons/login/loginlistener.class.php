<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information (GPL).
 * 
 * @package daemons
 **/

/**
 * @package daemons
 */
class LoginListener extends Listener
{
	function eventOccured(Event $event)
	{
		$currentUser = $this->userFactory->getCurrentUser();

		if ( isset($GLOBALS['config']['login']['firsttimechangepassword']) && ($GLOBALS['config']['login']['firsttimechangepassword'] === TRUE))
		{
			if ($currentUser->getPref('changePassword') === FALSE)
			{
				 //No changepassword pref set
				$this->messageManager->addMessage("default", "CHANGEPASSWORD");
			}
			elseif ($currentUser->getPref('changePassword') == 0)
			{
				 //No need to change password
			}
			elseif ($currentUser->getPref('changePassword') == 1)
			{
				 //User must change password
				$this->messageManager->addMessage("default", "CHANGEPASSWORD");
			}
		}
	}
}

?>
