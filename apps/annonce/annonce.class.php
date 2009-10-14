<?php 
/**
 * @copyright 2008 Gilles Dehaudt
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
class Annonce extends Model
{
	public function build()
	{	
		$app = $this->appList->getApp($this->appname);
        $config = $app->getConfig();
        $this->assign("config", $config);

		if ( isset($this->args['maxannonce']) )
		{
			$maxannonce = $this->args['maxannonce'];
		}
		else
		{
			$maxannonce = $config['maxannonce']['default'];
		}
		

		$sql = "SELECT * FROM annonce WHERE `expirationdate` > NOW() AND `visible`=1 ORDER BY datetime DESC LIMIT :maxannonce";
		try
		{
			$stmt = $this->db->prepare($sql);
			$stmt->bindParam(":maxannonce",intval($maxannonce),PDO::PARAM_INT);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		$annonces = array();
		while (($annonceRow = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
			//je recupere l'user
			if (($user["object"] =  $this->userFactory->prepareUserFromId($annonceRow["Id_user"])) !== false) {
				$annonces[] = array(
					"author" => $user['object'],
					"text" => $annonceRow['annonce'],
					"price" => $annonceRow['price'],
					"expirationdate" => $annonceRow['expirationdate'],
					"id" => $annonceRow['Id'],
					"iduser" => $annonceRow['Id_user']
				);
			}
		}

		$this->assign("annonces",$annonces);
		$this->assign("islogged", $this->currentUser->isLogged());
		$this->assign("currentuser", $this->currentUser->getID());
        $this->assign("isadmin", $this->currentUser->isGlobalAdmin($this->db));
	}
}
