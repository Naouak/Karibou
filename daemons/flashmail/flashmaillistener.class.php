<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2008-2009 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information (GPL).
 * 
 * @package daemons
 **/

/**
 * @package daemons
 */
class FlashMailListener extends Listener
{
	function eventOccured(Event $event)
	{
		$user = $this->userFactory->getCurrentUser();
		if( $user->isLogged() )
		{
			if ( !isset($GLOBALS['config']['noflashmail']) || (isset($GLOBALS['config']['noflashmail']) && $GLOBALS['config']['noflashmail'] !== TRUE))
			{
				$flashmail = $this->appList->getApp('flashmail');
			}
		}
	}
}

?>
