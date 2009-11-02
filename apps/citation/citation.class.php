<?php 
/**
 * @copyright 2005 Charles Anssens
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
class Citation extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();

		// duree de vie min d une citation 
		$min_t2l = $config["max"]["time2live"];

		$sql = "SELECT * FROM citation WHERE (UNIX_TIMESTAMP(datetime) <= NOW()) AND deleted=0 ORDER BY datetime DESC LIMIT 1 ";
		try
		{
			$stmt = $this->db->query($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		if ($citationonline = $stmt->fetch(PDO::FETCH_ASSOC)) {
			// Get the user object associated with this user id
			if ($user["object"] =  $this->userFactory->prepareUserFromId($citationonline["user_id"])) {
				$name=$this->appname."-".$citationonline['Id'];
				$combox = new CommentSource($this->db,$name,"",$citationonline["citation"]);
				
				$this->assign("citationnow",$citationonline["citation"]);
				$this->assign("citationauthor",$user);
				$this->assign("islogged", $this->currentUser->isLogged());

				$this->assign("idcombox",$combox->getId());
				$this->assign("author_id",$citationonline["user_id"]);
				$this->assign("citation_id",$citationonline["id"]);
				$this->assign("isadmin", $this->getPermission() == _ADMIN_);
				$this->assign("currentuser",$this->currentUser->getId());
			}
		}
	}
}

?>
