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
		$moods = array(-1 => "none", "happy", "sad", "angry", "bored", "depressed", "disgusted", "excited", "invincible", "proud", "sick", "in_love", "stressed", "surprised", "worried", "serious", "distracted", "working", "desperate", "furious");
		
		// Lister tous les utilisateurs en ligne dans un tableau
		$sql = "SELECT ou.*, um.message, um.mood, fp.gender FROM onlineusers ou 
                    LEFT JOIN usermood um ON um.user_id = ou.user_id
                    LEFT JOIN " . $GLOBALS['config']['bdd']["frameworkdb"] . ".users fu ON fu.id = ou.user_id
                    LEFT JOIN " . $GLOBALS['config']['bdd']["frameworkdb"] . ".profile fp ON fp.id=fu.profile_id
		    WHERE ou.timestamp > :time ORDER BY ou.user_id";
		try {
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":time", time() - 600);
			$stmt->execute();
		} catch(PDOException $e) {
			Debug::kill($e->getMessage());
		}
		$onlineusers = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$g = GeolocationFactory::getInstance();
		foreach($onlineusers as &$user) {
			$user["object"] = $this->userFactory->prepareUserFromId($user["user_id"]);
			$g->prepareForUser($user["object"]);
			$user["message"] = $user["message"];
			if (isset($user["mood"]) && ($user["mood"] != -1)) {
				$tmp = gettext("MOOD_" . $moods[$user["mood"]]);
				if ($user["gender"] == "woman") {
					$tmp = gettext("MOODE_" . $moods[$user["mood"]]);
					if ($tmp == "MOODE_" . $moods[$user["mood"]])
						$tmp = gettext("MOOD_" . $moods[$user["mood"]]);
				}
				$user["mood"] = $tmp;
			} else
				$user["mood"] = "";
		}
	
		$nbonlineusers= sizeof($onlineusers);
		$this->assign("nbonlineusers", $nbonlineusers);
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
            
		// Find the current user gender
		$sql = "SELECT p.gender FROM " . $GLOBALS['config']['bdd']["frameworkdb"] . ".users u LEFT JOIN " . $GLOBALS['config']['bdd']["frameworkdb"] . ".profile p ON p.id=u.profile_id WHERE u.id=". $this->currentUser->getID();
		$isGirl = false;
		try {
			$stmt = $this->db->query($sql);
			$userGender = $stmt->fetch();
			$isGirl = ($userGender[0] == "woman");
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}
		$moodsDisplayList = array(-1 => "MOOD_none");
		foreach ($moods as $moodValue => $moodText) {
			$tmp = gettext("MOOD_$moodText");
			if ($isGirl)
				if (gettext("MOODE_$moodText") != "MOODE_$moodText")
					$tmp = gettext("MOODE_$moodText");
			$moodsDisplayList[$moodValue] = $tmp;
		}
		$this->assign("moodList", $moodsDisplayList);
	}

}

?>
