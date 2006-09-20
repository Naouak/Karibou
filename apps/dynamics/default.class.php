<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/

/**
 *
 * @package applications
 **/
class DynamicsDefault extends Model
{
	public function build()
	{
		$app = $this->appList->getApp('onlineusers');
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
		
		//Définition de l'utilisation des flashmails
		if (isset($GLOBALS['config']['noflashmail']))
			$this->assign("noflashmail", $GLOBALS['config']['noflashmail']);
		else
			$this->assign("noflashmail", FALSE);
	}
}

?>