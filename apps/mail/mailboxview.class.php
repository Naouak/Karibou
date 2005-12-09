<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MailboxView extends Model
{
	protected $useTrash = FALSE;

	function build()
	{
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "home") );
	
		$user = $this->userFactory->getCurrentUser();
		
		$keychain = KeyChainFactory::getKeyChain($this->currentUser);
		if( !$keychain->unlock() )
		{
			$this->assign("message", 'cannot unlock the KeyChain');
			return;
		}
		
		$config = $this->getConfig();
		
		$pass = $keychain->get('session_password');
		$username = $user->getLogin().$config['username_append'];
		$server = $config['server'];
		
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
			$this->assign('loggedin', true);
			$this->assign('quota', $mailbox->getQuota() );
			$this->assign('mailboxes', $mailbox->getMailboxes() );
			
			if( isset($this->args['uid']) && isset($this->args['attachment']) )
			{ // recuperation des pieces jointes
				// on arrete le debug pour la recuperation des pieces jointes
				Debug::$display = false;
				
				$message = new Message($mailbox, $this->args['uid']);
				$att = $message->getAttachments();
				foreach($att as $a)
				{
					if( $a->getPartNo() == $this->args['attachment'] )
					{
						header("Content-Type: ".$a->getContentType() );
						header("Content-Size: ".$a->getBytes() );
						$attrs = $a->getAttributes();
						if( isset($attrs['name']) )
						{
							header('Content-Disposition: attachment; filename="'.$attrs['name'].'"');
						}
						$this->assign("attachment", $a->getPart());
					}
				}
			}
			else if( isset($this->args['uid']) )
			{ // affichage d'un message
				$message = new Message($mailbox, $this->args['uid']);
				$this->assign('header', $message->getHeader());
				
				$part = $message->getBody();
				$body = "";
				if( $part->getSubtype() == "plain" )
				{
//						$body = "<pre>".htmlentities(wordwrap($part->getPart()), ENT_COMPAT, $part->outencoding )."</pre>" ;
					$body = "<pre>".$part->getPart()."</pre>" ;
				}
				else if( $part->getSubtype() == "html" )
				{
					$body = $part->cleanHTML($part->getPart());
				}
				$this->assign('body', $body);
				
				$msgno = $mailbox->getMessageNumber($this->args['uid']);
				
				$this->assign('attachments', $message->getAttachments());
				$this->assign('uid', $this->args['uid']);
				if( $msgno < $mailbox->getMessageCount() )
				{
					$this->assign('uid_next', $mailbox->getUid($msgno + 1));
				}
				if( $msgno > 0 )
				{
					$this->assign('uid_prev', $mailbox->getUid($msgno - 1));
				}
			}
			else
			{ // affichage de la liste des messages
				if( isset($this->args['hide']) && ($this->args['hide']=='showall') )
				{
					$mailbox->displayDeleted();
					$this->assign("hide_link", true);
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
		else
		{
			$this->assign('message', 'cannot connect');
		}
		$this->assign ("username", $username);
		unset($mailbox);
	}
}
 
?>
