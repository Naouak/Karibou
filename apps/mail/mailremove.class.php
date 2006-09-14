<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MailRemove extends FormModel
{
	function build()
	{
		Debug::$display = false;
		
		if( isset($_GET['mailbox'], $_GET['uid']) )
		{
			$user = $this->userFactory->getCurrentUser();
			
			$keychain = KeyChainFactory::getKeyChain($user);
			if( !$keychain->unlock() )
			{
				$this->assign("message", 'cannot unlock the KeyChain');
				return;
			}
			
			$config = $this->getConfig();
			
			$pass = $keychain->get('session_password');
			$username = $user->getLogin().$GLOBALS['config']['login']['post_username'];
			$server = $GLOBALS['config']['login']['server'];
			
			
			if( !empty($_GET['mailbox']) )
			{
				$mailbox = new Mailbox($server, $username, $pass, $_GET['mailbox']) ;
				$this->setRedirectArg("mailbox", $_GET['mailbox']);
			}
			else
			{
				$mailbox = new Mailbox($server, $username, $pass, "INBOX") ;
			}
			if( $mailbox->connected() )
			{
				$mailbox->messageRemove($_GET['uid']);
			}
		}
	}
}
 
?>
