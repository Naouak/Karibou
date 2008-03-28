<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MailMove extends Model
{
	function build()
	{
		Debug::$display = false;
		
		if( isset($_POST) )
		{
			$user = $this->userFactory->getCurrentUser();
			
			$keychain = KeyChainFactory::getKeyChain($this->currentUser);
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
			
			if( !empty($this->args['mailbox']) )
			{
				$mailbox = new Mailbox($server, $username, $pass, $this->args['mailbox']) ;
				$this->assign('mailbox', $this->args['mailbox']);
			}
			else
			{
				$this->assign('mailbox', 'INBOX');
				$mailbox = new Mailbox($server, $username, $pass, "INBOX") ;
			}
			if( $mailbox->connected() )
			{
				foreach( $_POST as $name => $value )
				{
					if( preg_match('/mail_([0-9]+)/', $name, $match) )
					{
						$uid = $match[1];
						$dest_folder = $value;
						$mailbox->messageMove($uid, $dest_folder);
					}
				}
				
				$this->assign('messagecount', $mailbox->getMessageCount());
				if( isset($this->args['pagenum']) && ($this->args['pagenum']>0) )
				{
					$pagenum = $this->args['pagenum'];
				}
				else
				{
					$pagenum = 1;
				}
				$this->assign('messageheaders', array_reverse($mailbox->getHeaders($pagenum)));
				$this->assign('page', $pagenum);
				$this->assign('pagecount', $mailbox->getPageCount());
			}
		}
	}
}
 
?>
