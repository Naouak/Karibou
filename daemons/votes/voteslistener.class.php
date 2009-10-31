<?php
/**
 * @copyright 2009 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information (GPL).
 * 
 * @package daemons
 **/

/**
 * @package daemons
 */
class VotesListener extends Listener
{
	public function eventOccured(Event $event)
	{
		$user = $this->userFactory->getCurrentUser();
		if( $user->isLogged() )
		{
			$votes = $this->appList->getApp('votes');
		}
	}
}

?>
