<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class GiveEmail extends Model
{

	public function build()
	{
		if (isset($_SESSION["emailforwardMessage"]))
		{
			$this->assign ("message", $_SESSION["emailforwardMessage"]);
			unset($_SESSION["emailforwardMessage"]);
		}

		//$ei = new EmailInterfaceLDAP($GLOBALS["config"]["ldap"]["rdn"],$GLOBALS["config"]["ldap"]["pwd"],$GLOBALS["config"]["ldap"]["jvd"]);	
		//$emaillogin = $this->currentUser->getLogin().$GLOBALS['config']['login']['post_username'];
		//$this->assign("emaillogin", $emaillogin);
		//$this->assign("email", $ei->getMailDrop($emaillogin));

	}
}

?>
