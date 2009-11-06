<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class KeyChainPost extends FormModel
{
	function build()
	{
		$keychain = KeyChainFactory::getKeyChain($this->currentUser);

		if( isset($_POST['unlock'], $_POST['pass']) )
		{
			if( isset($_POST['_crypt']) )
			{
				$krypt = new Krypt();
				$pass = $krypt->decrypt( $_POST['pass'] );
			}
			else
			{
				$pass = ( $_POST['pass'] );
			}
			$passphrase = sha1($pass);
			Debug::display($passphrase);
			if( $keychain->unlock($passphrase) )
			{
				$this->currentUser->setPassPhrase($passphrase);
				$this->userFactory->saveCurrentUSer();
			}
		}
		else if (isset($_POST['relock'], $_POST['oldpass'], $_POST['newpass1'], $_POST['newpass2']))
		{
			if( $_POST['newpass1'] == $_POST['newpass2'] )
			{
				if( isset($_POST['_crypt']) )
				{
					$krypt = new Krypt();
					$oldpass = $krypt->decrypt( $_POST['oldpass'] );
					$newpass1 = $krypt->decrypt( $_POST['newpass1'] );
				}
				else
				{
					$oldpass = ( $_POST['oldpass'] );
					$newpass1 = ( $_POST['newpass1'] );
				}
				$oldpass = sha1($oldpass);
				if( $keychain->unlock($oldpass) )
				{
					$passphrase = sha1($newpass1) ;
					$keychain->relock( $passphrase );
					$this->currentUser->setPassPhrase($passphrase);
					$this->userFactory->saveCurrentUSer();
				}
			}
		}
		else if (isset($_POST['create'], $_POST['pass1'], $_POST['pass2']))
		{
			if( $_POST['pass1'] == $_POST['pass2'] )
			{
				if( isset($_POST['_crypt']) )
				{
					$krypt = new Krypt();
					$pass1 = $krypt->decrypt( $_POST['pass1'] );
				}
				else
				{
					$pass1 = $_POST['pass1'];
				}
				$passphrase = sha1($pass1) ;
				$keychain->create( $passphrase );
				$this->currentUser->setPassPhrase($passphrase);
				$this->userFactory->saveCurrentUSer();
			}
		}

	}
}
 
?>
