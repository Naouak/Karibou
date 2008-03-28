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
            if (isset($_SESSION['user_mail_login'])) {
                $username = $_SESSION['user_mail_login'];
            } else {
                $username = $user->getLogin();
            }
			$server = $GLOBALS['config']['mail']['server'];
			
			
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
