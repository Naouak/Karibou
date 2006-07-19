<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 
class OnlineUsersList extends Model
{
	public function build()
	{
	
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$maxage = $config["max"]["age"];

		//Lister tous les utilisateurs en ligne dans un tableau
		$sql = "SELECT * FROM onlineusers WHERE timestamp > '".(time()-$maxage)."' ";
		try
		{
			$stmt = $this->db->query($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
        $onlineusers = $stmt->fetchAll(PDO::FETCH_ASSOC);
		

        foreach($onlineusers as &$user)
        {
            $user["object"] = $this->userFactory->prepareUserFromId($user["user_id"]);
        }
        
        $this->userFactory->setUserList();

		$this->assign("onlineusers", $onlineusers);
		
		$this->assign("islogged", $this->currentUser->isLogged());
	}

}

?>
