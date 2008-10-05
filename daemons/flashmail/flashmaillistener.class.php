<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information (GPL).
 * 
 * @package daemons
 **/

ClassLoader::add('FlashMailFactory', KARIBOU_APP_DIR.'/flashmail/class/flashmailfactory.class.php');
ClassLoader::add('FlashMail', KARIBOU_APP_DIR.'/flashmail/class/flashmail.class.php');

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
                // Initialize the flashmail application ==> will automatically create the needed hooks
				$flashmail = $this->appList->getApp('flashmail');
				//$flashmail->addView("account_headerbox",	"footer_account_start");
				//$flashmail->addView("unreadlist",			"flashmail_unreadlist");
			}
		}
	}
}

?>
