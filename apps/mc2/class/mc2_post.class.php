<?php
/**
 * @copyright 2008-2010 Pierre Ducroquet <pinaraf@ducroquet.info>
 * @copyright 2009-2010 Rémy Sanchez <remy.sanchez@hyperthese.net>
 * @copyright 2009 Quentin Burny <tardiel@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 */

class Mc2Post extends FormModel {
	public function build() {
		$db = Database::instance();

		// Getting the message and filtering it
		$msg = trim(filter_input(INPUT_POST, "msg", FILTER_SANITIZE_SPECIAL_CHARS));

		// There is no point if the message is empty
		if(empty($msg)) {
			return;
		}

		// Check that the user has the permissions to post a message
		$app = $this->appList->getApp($this->appname);
		if ($app->getPermission() <= _READ_ONLY_) {
			return;
		}

		try {
			/*****
			 * Antiflood protection
			 *****/

			// Only 20 messages per minute are allowed
			$sth = $db->prepare("SELECT COUNT(*) FROM minichat WHERE id_auteur = :user AND `time` > SUBTIME(NOW(), '00:01:00')");
			$sth->bindValue(':user', $this->currentUser->getID());
			$sth->execute();
			if($sth->fetchColumn(0) > 20) {
				return;
			}

			/*****
			 * Games section
			 *****/

			// Alone on Karibou
			// -> if somebody says "alone on karibou" and that he is the only online
			//    person, then he wins 600000 points
			if(strcasecmp("alone on karibou", $msg) == 0) {
				$last_hour = $db->prepare("SELECT COUNT(*) FROM minichat WHERE id_auteur = :user AND post = 'alone on karibou' AND `time` > SUBTIME(NOW(), '01:00:00')");
				$last_hour->bindValue(":user", $this->currentUser->getID());
				$last_hour->execute();
				$can_play = ($last_hour->fetchColumn(0) == 0);
				if ($can_play) {
					// Check the type of game...
					$game_check_full = $db->prepare("SELECT COUNT(*) FROM onlineusers WHERE user_id <> :user");
					$game_check_full->bindValue(":user", $this->currentUser->getID());
					$game_check_full->execute();
					if ($game_check_full->fetchColumn(0) == 0) {
						ScoreFactory::addScoreToUser($this->currentUser, 600000, "alone on karibou");
					} else {
						$game_check_part = $db->prepare("SELECT COUNT(*) FROM onlineusers WHERE user_id <> :user AND away=1 OR TIME_TO_SEC(TIMEDIFF(NOW(), last_presence)) > 900");
						$game_check_part->bindValue(":user", $this->currentUser->getID());
						$game_check_part->execute();
						if ($game_check_part->fetchColumn(0) == 0) {
							ScoreFactory::addScoreToUser($this->currentUser, 300000, "alone on karibou");
						}
					}
				}
			}

			// Preums
			// -> The first person to say "preums" each day wins 500000 points, the
			//    first to say "deuz" after that wins 200000 points and the first to
			//    say "troiz" after that wins 100000 points
			if($msg == "preums" or $msg == "deuz" or $msg == "troiz") {
				$db->query("LOCK TABLES minichat_game WRITE, scores WRITE, minichat WRITE, kache WRITE");

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

				$sth = $db->query("SELECT id_auteur, post FROM minichat_game WHERE DATE(`time`) = DATE(NOW()) AND post IN ('preums', 'deuz', 'troiz') ORDER BY id ASC");
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

				$sth = $db->prepare("
					INSERT INTO
						minichat_game (id_auteur, post, time)
					VALUES
						(:user, :message, NOW())
				");
				$sth->bindValue(":user", $this->currentUser->getID());
				$sth->bindValue(":message", $msg);
				$sth->execute();

				$db->query("UNLOCK TABLES");
				$db->query("DELETE FROM minichat_game WHERE DATE(time) != DATE(NOW())");
			}

			// Dernz
			// -> The last person to say "dernz" a day wins 300000 points
			if($msg == "dernz") {
				$db->query("LOCK TABLES minichat WRITE, minichat_game WRITE, scores WRITE, kache WRITE");

				// Who did the last dernz ?
				$sth = $db->prepare("SELECT id_auteur FROM minichat_game WHERE DATE(time) = DATE(NOW()) AND post = 'dernz' ORDER BY id DESC LIMIT 1");
				$sth->execute();
				$last_user = $sth->fetchColumn(0);

				// Anti flood
				$sth = $db->prepare("SELECT COUNT(*) FROM minichat_game WHERE DATE(time) = DATE(NOW()) AND post = 'dernz' AND id_auteur = :user");
				$sth->bindValue(":user", $this->currentUser->getID());
				$sth->execute();
				$count = $sth->fetchColumn(0);

				if($this->currentUser->getID() != $last_user && $count == 0) {
					// Insert the last message
					$sth = $db->prepare("
						INSERT INTO
							minichat_game (id_auteur, post, time)
						VALUES
							(:user, :message, NOW())
					");
					$sth->bindValue(":user", $this->currentUser->getID());
					$sth->bindValue(":message", $msg);
					$sth->execute();

					$db->query("UNLOCK TABLES");
					$db->query("DELETE FROM minichat_game WHERE DATE(time) != DATE(NOW())");
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
				$stmt = $db->prepare("UPDATE onlineusers SET away = NOT(away) WHERE user_id = :user");
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

			$user = $this->userFactory->prepareUserFromId($this->currentUser->getID());

			// Notify the user of what happened
			try {
				$this->userFactory->setUserList();
			} catch(Exception $e) {
				// do nothing, this exception was excepted
			}
			$p = new KPantie();
			$evt = array(
				'time' => time() * 1000,
				'user_id' => $this->currentUser->getID(),
				'userlink' => userlink(array('noicon' => true, 'showpicture' => true, 'user' => $user), $this->appList),
				'post' => $msg,
				'type' => 'msg'
			);
			$p->throwEvent('mc2-*-message', json_encode($evt));
		} catch(Exception $ex) {
			// Ok you're fucked
		}
	}
}
