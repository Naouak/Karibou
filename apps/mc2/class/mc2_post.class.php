<?php
class Mc2Post extends FormModel {
	public function build() {
		$db = Database::instance();

		$msg = trim(filter_input(INPUT_POST, "msg", FILTER_SANITIZE_SPECIAL_CHARS));

		if(empty($msg)) {
			return;
		}

		try {
			/*****
			 * Antiflood protection
			 *****/

			$sth = $this->db->prepare("SELECT COUNT(*) FROM minichat WHERE id_auteur = :user AND `time` > SUBTIME(NOW(), '00:01:00')");
			$sth->bindValue(':user', $this->currentUser->getID());
			$sth->execute();
			if($sth->fetchColumn(0) > 20) {
				return;
			}

			/*****
			 * Games section
			 *****/

			// Alone on Karibou
			if(strcasecmp("alone on karibou", $msg) == 0) {
				$last_hour = $this->db->prepare("SELECT COUNT(*) FROM minichat WHERE id_auteur = :user AND post = 'alone on karibou' AND `time` > SUBTIME(NOW(), '01:00:00')");
				$last_hour->bindValue(":user", $this->currentUser->getID());
				$last_hour->execute();

				if($this->db->query("SELECT COUNT(*) FROM onlineusers")->fetchColumn(0) == 1 and $last_hour->fetchColumn(0) == 0) {
					ScoreFactory::addScoreToUser($this->currentUser, 600000, "alone on karibou");
				}
			}

			// Preums
			if($msg == "preums" or $msg == "deuz" or $msg == "troiz") {
				$this->db->query("LOCK TABLES minichat_game WRITE, scores WRITE, minichat WRITE, kache WRITE");

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
					&& !$res[$msg]
					&& (
						$msg == "preums"
						|| ($msg == "deuz" && $res["preums"])
						|| ($msg == "troiz" && $res["deuz"])
					)
				) {
					ScoreFactory::addScoreToUser($this->currentUser, $scores[$msg], "preums");
				}

				$sth = $this->db->prepare("
					INSERT INTO
						minichat_game (id_auteur, post, time)
					VALUES
						(:user, :message, NOW())
				");
				$sth->bindValue(":user", $this->currentUser->getID());
				$sth->bindValue(":message", $msg);
				$sth->execute();

				$this->db->query("UNLOCK TABLES");
				$this->db->query("DELETE FROM minichat_game WHERE DATE(time) != DATE(NOW())");
			}

			// Dernz
			if($msg == "dernz") {
				$this->db->query("LOCK TABLES minichat WRITE, minichat_game WRITE, scores WRITE, kache WRITE");

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
					$sth->bindValue(":message", $msg);
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
			 * /away
			 *****/
			if($msg == "/away"){
				$stmt = $this->db->prepare("UPDATE onlineusers SET away = MOD(away+1,2) WHERE user_id = :user");
				$stmt->bindValue(":user",$this->currentUser->getID());
				$stmt->execute();
				return;
			}

			/****
			 * Treatment of the message itself
			 ****/

			// Insert the message
			$sth = $db->prepare('INSERT INTO minichat (time, id_auteur, type, post) VALUES (NOW(), :uid, "msg", :msg)');
			$sth->bindValue(':uid', $this->currentUser->getID());
			$sth->bindValue(':msg', $msg);
			$sth->execute();

			// Notify the user of what happened
			try {
				$this->userFactory->setUserList();
			} catch(Exception $e) {
				// do nothing, this exception was excepted
			}
			$p = new Pantie();
			$evt = array(
				'time' => time() * 1000,
				'user_id' => $this->currentUser->getID(),
				'userlink' => userlink(array('noicon' => true, 'showpicture' => true, 'user' => $this->currentUser), $this->appList),
				'post' => $msg,
				'type' => 'msg'
			);
			$p->throwEvent('mc2-*-message', json_encode($evt));
		} catch(Exception $ex) {
			// Ok you're fucked
		}
	}
}
