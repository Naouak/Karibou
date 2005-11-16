<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MailConnect extends FormModel
{
	function build()
	{
		$user = $this->userFactory->getCurrentUser();
		if( ! ($mailconnections = $user->getPref("mail_connections")) )
		{
			$mailconnections = array();
		}
		
		if( isset($_POST['use_saved']) )
		{
			$key = $_POST['connection'];
			if( isset($mailconnections[$key]) )
			{
				$this->setRedirectArg("serv", $key);
			}
		}
		else
		{
			$conn = array();
			$conn['host'] = $_POST['host'];
			$conn['login'] = $_POST['login'];
			
			$keychain = new KeyChain($this->db, $this->currentUser);
			if( $keychain->unlock() )
			{
				if( isset($_POST['_crypt']) )
				{
					$krypt = new Krypt();
					$pass = $krypt->decrypt( $_POST['pass'] );
				}
				$keychain->set($conn['login']."@".$conn['host'] , $pass);

				if( isset($_POST['ssl']) )
				{
					$conn['ssl'] = $_POST['ssl'];
				}
				$mailconnections[] = $conn;
				foreach($mailconnections as $key => $value) { }
				$this->setRedirectArg("serv", $key);
				$user->setPref("mail_connections", $mailconnections);
			}
			else
			{
				$this->setRedirectArg("page", "failed");
				$this->setRedirectArg("message", "KEYCHAINLOCK");
			}
		}
	}
}
 
?>
