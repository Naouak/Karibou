<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 * @copyright 2008 Pierre Ducroquet <pinaraf@gmail.com>
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
		$moods = array(-1 => "none", "happy", "sad", "angry", "bored", "depressed", "disgusted", "excited", "invincible", "proud", "sick", "in_love", "stressed", "surprised", "worried", "serious", "distracted");
		$moodsDisplayList = array(-1 => "MOOD_none");
		foreach ($moods as $moodValue => $moodText) {
			$moodsDisplayList[$moodValue] = gettext("MOOD_$moodText");
		}
		$this->assign("moodList", $moodsDisplayList);
		
		if (isset($_POST["userState"])) {
			try {
				$this->db->query("INSERT INTO usermood(user_id, mood) VALUES(" . $this->currentUser->getID() . ", -1)");
			} catch (PDOException $e) {
				// Ignore.
			}
			$sql = "UPDATE usermood SET message=:userState, mood=:userMood WHERE user_id=:user_id";
			try {
				$stmt = $this->db->prepare($sql);
				$stmt->bindValue("user_id", $this->currentUser->getID());
				$stmt->bindValue("userState", substr(filter_input(INPUT_POST, "userState", FILTER_SANITIZE_STRING), 0, 64));
				$stmt->bindValue("userMood", $_POST["userMood"]);
				$stmt->execute();
			} catch(PDOException $e) {
				Debug::kill($e->getMessage());
			}
		} else {
			$app = $this->appList->getApp($this->appname);
			$config = $app->getConfig();
			$maxage = $config["max"]["age"];
	
			//Lister tous les utilisateurs en ligne dans un tableau
			$sql = "SELECT ou.*, um.message, um.mood FROM onlineusers ou LEFT JOIN usermood um ON um.user_id = ou.user_id WHERE ou.timestamp > '".(time()-$maxage)."' ";
			try {
				$stmt = $this->db->query($sql);
			} catch(PDOException $e) {
				Debug::kill($e->getMessage());
			}
			$onlineusers = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($onlineusers as &$user) {
				$user["object"] = $this->userFactory->prepareUserFromId($user["user_id"]);
				$user["message"] = $user["message"];
				if (isset($user["mood"]) && ($user["mood"] != -1))
					$user["mood"] = gettext("MOOD_" . $moods[$user["mood"]]);
				else
					$user["mood"] = "";
			}
	
			$nbonlineusers= sizeof($onlineusers);
			$this->assign("nbonlineusers", $nbonlineusers);
			$this->userFactory->setUserList();
			$this->assign("onlineusers", $onlineusers);
			
			$this->assign("islogged", $this->currentUser->isLogged());
			
			// Are we using flashmails ?
			if (isset($GLOBALS['config']['noflashmail']))
				$this->assign("noflashmail", $GLOBALS['config']['noflashmail']);
			else
				$this->assign("noflashmail", FALSE);
			
			$sql = "SELECT message, mood FROM usermood WHERE user_id=" . $this->currentUser->getID();
			try {
				$stmt = $this->db->query($sql);
			} catch (PDOException $e) {
				Debug::kill($e->getMessage());
			}
			$moodQuery = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($moodQuery as &$mood) {
				$this->assign("currentUserState", $mood["message"]);
				$this->assign("currentUserMood", $mood["mood"]);
			}
		}
	}

}

?>
