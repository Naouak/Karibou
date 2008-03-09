<?php 

/**
 * @version $Id $
 * @copyright 2007 Antoine Reversat
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class Birthday extends Model
{
	public function build()
	{
	
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
	
		$appsdb = $GLOBALS['config']['bdd']['frameworkdb'];

		$today = date("m-d");

		$query = "SELECT u.login,p.birthday from ".$appsdb.".profile p, ".$appsdb.".users u where p.birthday like \"%".$today."\" and u.profile_id=p.id";	
		$bdayers = Array();
		foreach($this->db->query($query) as $row){
			$userobj = $this->userFactory->prepareUserFromLogin($row['login']);
			$this->userFactory->setUserList();
			$user['user'] = $userobj;
			$user['login'] = $row['login'];
			$user['firstname'] = $userobj->getFirstname();
			$user['lastname'] = $userobj->getLastname();
			$user['nickname'] = $userobj->getSurname();
			$user['age'] = explode("-",$row['birthday']);
			$user['age'] =  date("Y") - $user['age'][0];
			$bdayers[] = $user;
		}	
		$this->assign("islogged", $this->currentUser->isLogged());	
		$this->assign('bdayers', $bdayers);
	}
}

?>
