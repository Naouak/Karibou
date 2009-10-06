<?php 

/**
 * @copyright 2008 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MCPost extends FormModel
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$this->assign("config", $config);
		if ($app->getPermission() > _READ_ONLY_)
//		if ($this->currentUser->isLogged())
		{
			/* POST */
			if (isset($_POST['post']))
			{
				$message = $_POST['post'];
				if (get_magic_quotes_gpc()) {
					$message = stripslashes($message);
				}
			}
			$flooding = false;
			$flood_sql = "SELECT COUNT(*) FROM minichat WHERE id_auteur=:userId AND TIME_TO_SEC(TIMEDIFF(NOW(), `time`)) < 60";
			try {
				$stmt = $this->db->prepare($flood_sql);
				$stmt->bindValue(":userId", $this->currentUser->getID());
				$stmt->execute();
				$row = $stmt->fetch();
				if ($row[0] >= 20)
					$flooding = true;
			} catch (PDOException $e) {
				Debug::kill($e->getMessage());
			}
			if ((isset($message)) && (strlen(trim($message)) > 0) && !$flooding)
			{
				/*****
				 * Games section
				 *****/

				// Alone on Karibou
				if(strcasecmp("alone on karibou", $message) == 0) {
					$last_hour = $this->db->prepare("SELECT COUNT(*) FROM minichat WHERE id_auteur = :user AND post = 'alone on karibou' AND `time` > SUBTIME(NOW(), '01:00:00')");
					$last_hour->bindValue(":user", $this->currentUser->getID());
					$last_hour->execute();

					if($this->db->query("SELECT COUNT(*) FROM onlineusers")->fetchColumn(0) == 1 and $last_hour->fetchColumn(0) == 0) {
						ScoreFactory::addScoreToUser($this->currentUser, 6000, "alone on karibou");
					}
				}

				// Preums
				if($message == "preums" or $message == "deuz" or $message == "troiz") {
					try {
						$this->db->query("LOCK TABLE minichat, scores WRITE");
					} catch(PDOException $e) {
					}

					$res = array(
						"preums" => false,
						"deuz" => false,
						"troiz" => false
					);

					$scores = array(
						"preums" => 5000,
						"deuz" => 2000,
						"troiz" => 1000
					);

					$winners = array();

					$sth = $this->db->query("SELECT id_auteur, post FROM minichat WHERE DATE(`time`) = DATE(NOW()) AND post IN ('preums', 'deuz', 'troiz') ORDER BY id ASC");
					while($row = $sth->fetch()) {
						if(!$res["preums"] && $row["post"] == "preums") {
							$res["preums"] = true;
							$winners[] = $row["id_auteur"];
						} elseif($res["preums"] && !$res["deuz"] && $row["post"] == "deuz" && !in_array($row["id_auteur"], $winners)) {
							$res["deuz"] = true;
							$winners[] = $row["id_auteur"];
						} elseif($res["deuz"] && !$res["troiz"] && $row["post"] == "troiz" && !in_array($row["id_auteur"], $winners)) {
							$res["troiz"] = true;
							$winners[] = $row["id_auteur"];
						}
					}

					if(
						!in_array($this->currentUser->getID(), $winners)
						&& !$res[$message]
						&& (
							$message == "preums"
							|| ($message == "deuz" && $res["preums"])
							|| ($message == "troiz" && $res["deuz"])
						)
					) {
						ScoreFactory::addScoreToUser($this->currentUser, $scores[$message], "preums");
					}

					try {
						$this->db->query("UNLOCK TABLES");
					} catch(PDOException $e) {
					}
				}

				// Dernz
				if($message == "dernz") {
					try {
						$this->db->query("LOCK TABLE minichat, scores WRITE");
					} catch(PDOException $e) {
					}

					// Lookup for the last dernz
					$sth = $this->db->prepare("SELECT id_auteur FROM minichat WHERE DATE(`time`) = DATE(NOW()) AND post = 'dernz' ORDER BY id DESC LIMIT 1");
					$sth->bindValue(":user", $this->currentUser->getID());
					$sth->execute();

					$sth2 = $this->db->prepare("SELECT COUNT(*) FROM minichat WHERE post = 'dernz' AND DATE(`time`) = DATE(NOW()) AND id_auteur = :user");
					$sth2->bindValue(":user", $this->currentUser->getID());
					$sth2->execute();

					if($sth2->fetchColumn(0) == 0) {
						if($row = $sth->fetch()) {
							if($row["id_auteur"] != $this->currentUser->getID())
								ScoreFactory::stealScoreFromUser($this->currentUser, $this->userFactory->prepareUserFromId($row["id_auteur"]), 3000, "preums");
						} else {
							ScoreFactory::addScoreToUser($this->currentUser, 3000, "preums");
						}
					}

					try {
						$this->db->query("UNLOCK TABLES");
					} catch(PDOException $e) {
					}
				}

				/*****
				 * Message insertion
				 *****/

				$req_sql = "INSERT INTO minichat 
					(time, id_auteur, post) VALUES
					(NOW(), :userId, :message)";
				try
				{
					$stmt = $this->db->prepare($req_sql);
					$stmt->bindValue(":userId", $this->currentUser->getID());
					$stmt->bindValue(":message", htmlspecialchars($message));
					$stmt->execute();
				}
				catch(PDOException $e)
				{
					Debug::kill($e->getMessage());
				}
			}
		}
	}
}

?>
