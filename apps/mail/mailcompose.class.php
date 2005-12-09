<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

ClassLoader::add('ProfileFactory', dirname(__FILE__).'/../annuaire/classes/profilefactory.class.php');
ClassLoader::add('Profile', dirname(__FILE__).'/../annuaire/classes/profile.class.php');

class MailCompose extends Model
{
	protected $wrap = 80;
	
	function build()
	{
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "compose") );
	
		$user = $this->userFactory->getCurrentUser();
		$username = $user->getLogin();
		$factory = new ProfileFactory($this->db, $GLOBALS['config']['bdd']["annuairedb"].".profile");
		if( $p = $factory->fetchFromUsername($username) )
		{
			$factory->fetchEmails($p);
			$all_emails = $p->getEmails();
			$from = array();
			foreach($all_emails as $e)
			{
				if( $e['type'] == 'INTERNET' ) $from[] = $e;
			}
			$this->assign('from_table', $from);
		}

		if( isset($this->args['mailbox'], $this->args['uid']) )
		{
			$keychain = KeyChainFactory::getKeyChain($user);
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
				$message = new Message($mailbox, $this->args['uid']);
				$header = $message->getHeader() ;
				//Debug::kill($message->getHeaderPart()->getPart());

				$from = $this->getAddressList($header->from);
				$this->assign('from', $from);
				
				if( isset($this->args['opt']) && ($this->args['opt']=='all') )
				{
					$to_array = array();
					$to_array = $this->mergeAddresses($to_array, $header->from);
					$to_array = $this->mergeAddresses($to_array, $header->to);
					if( isset($header->cc) ) $to_array = $this->mergeAddresses($to_array, $header->cc);
					if( isset($header->reply_to) )
					{
						$to_array = $this->mergeAddresses($to_array, $header->reply_to);
					}
					$txt_to = $this->getAddressList($to_array);
				}
				else
				{
					if( isset($header->reply_to) )
					{
						$txt_to = $this->getAddressList($header->reply_to);
					}
					else
					{
						$txt_to = $this->getAddressList($header->sender);
					}
				}
				
				$subject = "";
				if( isset($header->subject) )
				{
					$subject = $header->subject;
				}
				$this->assign('subject', 'Re : '.$subject);
				$this->assign('to', htmlentities($txt_to));

				$part_body = $message->getBody() ;

				$body = "";
				if( $part_body->getSubtype() == "plain" )
				{
					$body .= $this->formatReply($part_body->getPart());
				}
				else if( $part_body->getSubtype() == "html" )
				{
					$body .= $this->formatReply($this->cleanHTML($part_body->cleanHTML($part_body->getPart())));
				}
				$this->assign('body', $body);
			}
		}
	}
	
	function mergeAddresses($dst, $src)
	{
		foreach($src as $src_addr)
		{
			$insert = true;
			foreach($dst as $dst_addr)
			{
				if( ($dst_addr->mailbox == $src_addr->mailbox) &&
					($dst_addr->host == $src_addr->host) )
				{
					$insert = false;
					break;
				}
			}
			if( $insert )
			{
				$dst[] = $src_addr;
			}
		}
		return $dst;
	}
	
	function getAddressList($tab)
	{
		$addrlist = "";
		$first = true;
		foreach($tab as $s)
		{
			if( !$first ) $addrlist .= ', ';
			if( !isset( $s->personal) )
				$s->personal = $s->mailbox;
			$addrlist .= imap_rfc822_write_address($s->mailbox, $s->host, MailBox::headerDecode($s->personal, 'iso-1') );
			$first = false;
		}
		return $addrlist;
	}
	
	function formatReply($str)
	{
		$str = wordwrap($str, $this->wrap-2);
		$tab = explode("\n", $str);
		$ret = "";
		foreach($tab as $txt)
		{
			$ret .="> ".$txt."\n";
		}
		return $ret;
	}
		
	function cleanHTML($str)
	{
		$from = array(
			"@[\n\r]@si",
			"@<br[^>]*?>@si",
			"@</div>@si",
			"@<[^>]*?>@si",
		);
		$to = array( 
			"",
			"\n", 
			"\n", 
			"",
		);
		return preg_replace($from, $to, $str);
	}
}
 
?>
