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
					$this->db->query("LOCK TABLES minichat_game WRITE, scores WRITE, minichat WRITE");

					$res = array(
						"preums" => false,
						"deuz" => false,
						"troiz" => false
					);

					$scores = array(
						"preums" => 500000,
						"deuz" => 200000,
						"troiz" => 100000
					);

					$winners = array();

					$sth = $this->db->query("SELECT id_auteur, post FROM minichat_game WHERE DATE(`time`) = DATE(NOW()) AND post IN ('preums', 'deuz', 'troiz') ORDER BY id ASC");
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

					$sth = $this->db->prepare("
						INSERT INTO
							minichat_game (id_auteur, post, time)
						VALUES
							(:user, :message, NOW())
					");
					$sth->bindValue(":user", $this->currentUser->getID());
					$sth->bindValue(":message", $message);
					$sth->execute();

					$this->db->query("UNLOCK TABLES");
					$this->db->query("DELETE FROM minichat_game WHERE DATE(time) != DATE(NOW())");
				}

				// Dernz
				if($message == "dernz") {
					$this->db->query("LOCK TABLES minichat WRITE, minichat_game WRITE, scores WRITE");

					// Who did the last dernz ?
					$sth = $this->db->prepare("SELECT id_auteur FROM minichat_game WHERE DATE(time) = DATE(NOW()) AND post = 'dernz' ORDER BY id DESC LIMIT 1");
					$sth->execute();
					$last_user = $sth->fetchColumn(0);

					// Anti flood
					$sth = $this->db->prepare("SELECT COUNT(*) FROM minichat_game WHERE DATE(time) = DATE(NOW()) AND post = 'dernz' AND id_auteur = :user");
					$sth->bindValue(":user", $this->currentUser->getID());
					$sth->execute();
					$count = $sth->fetchColumn(0);

					if($this->currentUser->getID() != $last_user && $count == 0) {
						// Insert the last message
						$sth = $this->db->prepare("
							INSERT INTO
								minichat_game (id_auteur, post, time)
							VALUES
								(:user, :message, NOW())
						");
						$sth->bindValue(":user", $this->currentUser->getID());
						$sth->bindValue(":message", $message);
						$sth->execute();

						$this->db->query("UNLOCK TABLES");
						$this->db->query("DELETE FROM minichat_game WHERE DATE(time) != DATE(NOW())");
					}

					if($this->currentUser->getID() != $last_user && $count == 0) {
						if($last_user != false) {
							ScoreFactory::stealScoreFromUser($this->currentUser, $this->userFactory->prepareUserFromId($last_user), 300000, "preums");
						} else {
							ScoreFactory::addScoreToUser($this->currentUser, 300000, "preums");
						}
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
