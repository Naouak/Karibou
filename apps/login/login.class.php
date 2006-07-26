<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

ClassLoader::add('AuthMysql', dirname(__FILE__).'/authmysql.class.php');
ClassLoader::add('AuthImap', dirname(__FILE__).'/authimap.class.php');

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
			$pass = $_POST['_pass'];
			
			if( isset($_POST['_crypt']) )
			{
				$krypt = new Krypt();
				$pass = $krypt->decrypt( $_POST['_crypt'] );
			}
			
			if (!preg_match('/^([a-zA-Z0-9\.\-_]+)@([a-zA-Z0-9\.\-_]+)$/', $user, $match))
			{
				if( isset($GLOBALS['config']['login']['post_username']) )
				{
					$auth_user = $user . $GLOBALS['config']['login']['post_username'];
				}
				else
				{
					$auth_user = $user;
				}
			}
			else
			{
				$auth_user = $user;
				$user = $match[1]; //offset 1 is username
				
			}
			
			switch($GLOBALS['config']['login']['backend'])
			{
				case 'mysql':
					$auth = new AuthMysql($this->db);
					break;
				case 'imap':
					$auth = new AuthImap();
					break;
				default:
					Debug::kill("No Auth back-end selected");
					break;
			}
			if( $auth->login($auth_user, $pass) )
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
					$this->eventManager->sendEvent("login");
					
					if( isset($GLOBALS['config']['login']['savepassword']) && $GLOBALS['config']['login']['savepassword'])
					{
						$keychain = KeyChainFactory::getKeyChain($this->currentUser);
						//JoN check please...
						if ($keychain !== FALSE)
						{
							if ( $keychain->unlock() )
							{
								$keychain->set("session_password", $pass);
							}
							else
							{
								$names = $keychain->getNames();
								if( ! in_array('keychain_check', $names) )
								{
									$keychain->create();
									$keychain->set("session_password", $pass);
								}							
							}
						}
						else
						{
							//What to do ?
						}
					}
				}
			}
			else // Login Failed
			{
				$this->formMessage->add (FormMessage::FATAL_ERROR, gettext("LOGINFAILED"));
				$this->formMessage->setSession();
			}
		}
	}
}

?>
