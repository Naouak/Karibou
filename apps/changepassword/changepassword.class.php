<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class ChangePassword extends FormModel
{

	public function build()
	{
		if (isset($_POST["currentpassword"], $_POST["newpassword1"], $_POST["newpassword2"]))
		{
			if (strlen($_POST["newpassword1"]) < 3)
			{
				//Password can't be empty
				$_SESSION["changepasswordMessage"] = "MINLENGTH";
			}
			elseif ($_POST["newpassword1"] != $_POST["newpassword2"])
			{
				//Passwords don't match
				$_SESSION["changepasswordMessage"] = "DONTMATCH";
			}
			elseif ($_POST["newpassword1"] == $_POST["newpassword2"])
			{
			
					$app = $this->appList->getApp($this->appname);
					$config = $app->getConfig();

					$login = $this->currentUser->getLogin();
					$oldpassword = stripslashes($_POST["currentpassword"]);
					$newpassword = stripslashes($_POST["newpassword1"]);
					$authManager = new AuthManager($this->db);
					if( !$authManager->changePassword($login, $oldpassword, $newpassword) )
					{
						$_SESSION["changepasswordMessage"] = "UNKNOWNERROR";
						Debug::kill($mail);
					}
					else
					{

						$keychain = KeyChainFactory::getKeyChain($this->currentUser);
						if (!$keychain->unlock())
							Debug::kill("Not authorized...");
						$keychain->set('session_password', $_POST["newpassword1"]);
						$keychain->relock(sha1($_POST["newpassword1"]));
                        $this->currentUser->setPassPhrase(sha1($_POST["newpassword1"]));
						$_SESSION["changepasswordMessage"] = "OK";
						$this->currentUser->setPref('changePassword', 0);
						$this->currentUser->savePrefs($this->db);
                        $this->userFactory->saveCurrentUser();
					}
			}
			else
			{
				Debug::kill("??? What the hell ???");
			}
			$this->setRedirectArg('page', '');

		}
		else
		{
			Debug::kill("No POST data...");
		}
	}
}

?>
