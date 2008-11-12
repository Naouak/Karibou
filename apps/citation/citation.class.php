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
		$citation = filter_input(INPUT_POST, 'newcitation', FILTER_SANITIZE_SPECIAL_CHARS);
		if (($citation !== false) && ($this->currentUser->isLogged()) ) 
		{
			if (strlen($citation) > 3)
			{
				//Requete d'insertion
				//@TODO : handle time to live here...
				$sql = "INSERT INTO citation (user_id, citation, datetime) VALUES (:user, :citation, NOW());";
				$stmt = $this->db->prepare("INSERT INTO citation(user_id, citation, datetime) VALUES (:user, :citation, NOW());");
				$stmt->bindValue(":user", $this->currentUser->getId());
				$stmt->bindValue(":citation", $citation);
				$stmt->execute();
			}
			
		}

		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();

		// duree de vie min d une citation 
		$min_t2l = $config["max"]["time2live"];

		$sql = "SELECT * FROM citation WHERE (UNIX_TIMESTAMP(datetime) <= NOW()) ORDER BY datetime DESC LIMIT 1 ";
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
				$this->assign("citationnow",$citationonline["citation"]);
				$this->assign("citationauthor",$user);
				$this->assign("islogged", $this->currentUser->isLogged());
			}
		}
	}
}

?>
