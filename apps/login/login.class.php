<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2008 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

/**
 * Used to Log-in a user
 * 
 * @package applications
 */

class Login extends FormModel
{
	function build()
	{
		if (isset ($_POST['_user']) && isset ($_POST['_pass']))
		{
			$user = $_POST['_user'];
			$pass = stripslashes($_POST['_pass']);
			
			if( isset($_POST['_crypt']) )
			{
				$krypt = new Krypt();
				$pass = $krypt->decrypt( $_POST['_crypt'] );
			}
			
			if (preg_match('/^([a-zA-Z0-9\.\-_]+)@([a-zA-Z0-9\.\-_]+)$/', $user, $match))
			{
				$user = $match[1];
			}
			$authManager = new AuthManager($this->db);
			if ($authManager->checkPassword($user, $pass))
			{
				$created = FALSE;
				if( isset($GLOBALS['config']['login']['createuser']) && $GLOBALS['config']['login']['createuser'] )
				{
					$this->userFactory->setCurrentUser($user, TRUE );
					$created = TRUE;
				}
				else if( $this->userFactory->setCurrentUser($user) )
				{
					$created = TRUE;
				}
				if( $created )
				{
					$this->currentUser = $this->userFactory->getCurrentUser() ;
					$this->currentUser->login();
					$this->currentUser->setPassPhrase(sha1($pass));
					$this->userFactory->saveCurrentUser();
					
					if( isset($GLOBALS['config']['login']['savepassword']) && $GLOBALS['config']['login']['savepassword'])
					{
						$keychain = KeyChainFactory::getKeyChain($this->currentUser);
						
						if ($keychain !== FALSE)
						{
							if ( $keychain->unlock() )
							{
								// This keychain is clean, just store the session password there...
								$keychain->set("session_password", $pass);
							}
							else
							{
								// This keychain is not clean, we couldn't unlock it !
								$names = $keychain->getNames();
								if( ! in_array('keychain_check', $names) )
								{
									// No keychain_check in the keychain => it is a new keychain.
									$keychain->create();
									$keychain->set("session_password", $pass);
								}
								else
								{
									// Ok, we are running into a problem with the KeyChain
									// We can't unlock it, probably because the password changed recently.
									// We still need to store the session password somewhere... 
									// This will be deleted ASAP by the default app when the user has given his/her old password
									$_SESSION["temp_session_password"] = $pass;
								}
							}
						}
						else
						{
							//What to do ?
							Debug::kill("Unable to open the KeyChain");
						}
					}
					$this->eventManager->sendEvent("login");
				}
			}
			else // Login Failed
			{
				$this->formMessage->add (FormMessage::FATAL_ERROR, gettext("LOGINFAILED"));
				$this->formMessage->setSession();
				$this->eventManager->sendEvent("logout");
				$this->eventManager->performActions();
			}
		}
	}
}

?>
