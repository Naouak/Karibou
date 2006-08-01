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
			$flashmailFactory = new FlashMailFactory($this->db, $user, $this->userFactory, FALSE);

			$flashmail = $this->appList->getApp('flashmail');
			if ($flashmailFactory->checkNew())
			{
				//$flashmail->addView("popupscript", "html_head");
				//$flashmail->addView("headerbox", "page_content_start");
			}
			$flashmail->addView("account_headerbox",	"footer_account_start");
			$flashmail->addView("unreadlist",			"flashmail_unreadlist");
			$flashmail->addView("account_writebox",		"footer_account_start");
		}
	}
}

?>
