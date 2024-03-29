<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class Save extends FormModel
{
	protected $text;
	
	public function build()
	{
		if (isset($_POST["email"]))
		{
			$this->text = new KText();
			$ei = new EmailInterfaceLDAP($GLOBALS["config"]["ldap"]["rdn"],$GLOBALS["config"]["ldap"]["pwd"],$GLOBALS["config"]["ldap"]["jvd"]);	
			$emaillogin = $this->currentUser->getLogin().$GLOBALS['config']['login']['post_username'];
			if ( $this->text->checkEmail($_POST["email"]))
			{
					if( !$ei->changeMailDrop($emaillogin, $_POST["email"]) )
					{
						$_SESSION["emailforwardMessage"] = "UNKNOWNERROR";
						Debug::kill($ei);
					}
					else
					{
						$_SESSION["emailforwardMessage"] = "OK";
					}
			}
			elseif ($_POST["email"] == "")
			{
				if( !$ei->removeMailDrop($emaillogin) )
				{
					$_SESSION["emailforwardMessage"] = "UNKNOWNERROR";
					Debug::kill($ei);
				}
				else
				{
					$_SESSION["emailforwardMessage"] = "OK";
				}
			}
			else
			{
				$_SESSION["emailforwardMessage"] = "EMAILNOTVALID";
			}				

		}
		else
		{
			$_SESSION["emailforwardMessage"] = "UNKNOWNERROR";
			Debug::kill("No POST data...");
		}
		$this->setRedirectArg('page', '');
	}
}

?>