<?php
class ScoreManager {
	protected $db, $uf, $al;

	public function __construct($db, $uf, $al) {
		$this->db = $db;
		$this->uf = $uf;
		$this->al = $al;
	}

	public function addScoreToUser(User $user, $score, $app = "none", $silent = false) {
		$query =
			"INSERT INTO scores (user_id, score, app) ".
			"VALUES (:user, :score, :app) ";
		$sth = $this->db->prepare($query);
		$sth->bindValue(":score", $score);
		$sth->bindValue(":user", $user->getID());
		$sth->bindValue(":app", $app);
		$sth->execute();

		if(!$silent) {
			$msg = sprintf("/me a %s %d point%s ! (application: %s)", $score >= 0 ? "gagné" : "perdu", abs($score), abs($score) > 1 ? "s" : "", $app);
			$query =
				"INSERT INTO minichat (id_auteur, post, type) ".
				"VALUES (:user, :post, 'score')";
			$sth = $this->db->prepare($query);
			$sth->bindValue(":user", $user->getID());
			$sth->bindValue(":post", $msg);
			$sth->execute();
			KacheControl::instance()->renew("minichat");

			$this->uf->setUserList();
			$p = new Pantie();
			$evt = array(
				'time' => time() * 1000,
				'user_id' => $user->getID(),
				'userlink' => userlink(array('noicon' => true, 'showpicture' => true, 'user' => $user), $this->al),
				'post' => $msg,
				'type' => 'score'
			);
			$p->throwEvent('mc2-*-message', json_encode($evt));
		}
	}

	public function stealScoreFromUser(User $thief, User $victim, $score, $app = "none", $silent = false) {
		$query = "
			INSERT INTO scores (user_id, score, app)
			VALUES (:user, :score, :app)
		";
		$sth = $this->db->prepare($query);

		$sth->bindValue(":user", $thief->getID());
		$sth->bindValue(":score", $score);
		$sth->bindValue(":app", $app);
		$sth->execute();

		$sth->bindValue(":user", $victim->getID());
		$sth->bindValue(":score", -$score);
		$sth->bindValue(":app", $app);
		$sth->execute();

		if(!$silent) {
			// Nobody's seen that
			$this->uf->setUserList();

			$msg = sprintf("/me a volé %d points à %s (application: %s)", $score, $victim->getDisplayName(), $app);

			$query =
				"INSERT INTO minichat (id_auteur, post, type) ".
				"VALUES (:user, :post, 'score')";
			$sth = $this->db->prepare($query);
			$sth->bindValue(":user", $thief->getID());
			$sth->bindValue(":post", $msg);
			$sth->execute();
			KacheControl::instance()->renew("minichat");

			$p = new Pantie();
			$evt = array(
				'time' => time() * 1000,
				'user_id' => $thief->getID(),
				'userlink' => userlink(array('noicon' => true, 'showpicture' => true, 'user' => $thief), $this->al),
				'post' => $msg,
				'type' => 'score'
			);
			$p->throwEvent('mc2-*-message', json_encode($evt));
		}
	}

	public function getScoreOf(User $user, $app = null) {
		if(is_null($app)) {
			$query =
				"SELECT COALESCE(SUM(score), 0) AS score ".
				"FROM scores_total ".
				"WHERE user_id = :user ";
			$sth = $this->db->prepare($query);
			$sth->bindValue(":user", $user->getID());
			$sth->execute();

			return $sth->fetchColumn(0);
		} else {
			$query =
				"SELECT COALESCE(SUM(score), 0) AS score ".
				"FROM scores_valid ".
				"WHERE user_id = :user ".
				"AND app = :app ";
			$sth = $this->db->prepare($query);
			$sth->bindValue(":user", $user->getID());
			$sth->bindValue(":app", $app);
			$sth->execute();

			return $sth->fetchColumn(0);
		}
	}

	public function getRankOf(User $user, $app = null) {
		if($app !== null) {
			$sth = $this->db->prepare("
				SELECT rank
				FROM scores_app
				WHERE user_id = :user
				AND app = :app
			");
			
			$sth->bindValue(":app", $app);
		} else {
			$sth = $this->db->prepare("
				SELECT rank
				FROM scores_total
				WHERE user_id = :user
			");
		}

		$sth->bindValue(":user", $user->getID());
		$sth->execute();

		return $sth->fetchColumn(0);
	}

	public function getInvRankOf(User $user, $app = null) {
		if($app !== null) {
			$sth = $this->db->prepare("
				SELECT rank_inv
				FROM scores_app
				WHERE user_id = :user
				AND app = :app
			");
			
			$sth->bindValue(":app", $app);
		} else {
			$sth = $this->db->prepare("
				SELECT rank_inv
				FROM scores_total
				WHERE user_id = :user
			");
		}

		$sth->bindValue(":user", $user->getID());
		$sth->execute();

		return $sth->fetchColumn(0);
	}

	public function getNumberOfPlayers($app) {
		if ($app == null) {
			$sth = $this->db->prepare("SELECT COUNT(*) FROM (SELECT * FROM scores_valid GROUP BY user_id) AS t");
		} else {
			$sth = $this->db->prepare("SELECT COUNT(*) FROM (SELECT * FROM scores_valid WHERE app=:app GROUP BY user_id) AS t");
			$sth->bindValue(":app", $app);
		}
		$sth->execute();
		return $sth->fetchColumn(0);
	}

	public function getScoreBoard($num, $inv = false, $app = null) {
		if($inv) {
			$order = "DESC";
		} else {
			$order = "ASC";
		}
		
		if($app === null) {
			$where = "";
			$table = "scores_total";
		} else {
			$where = "WHERE app = " . $this->db->quote($app);
			$table = "scores_app";
		}
		
		$sth = $this->db->prepare("
			SELECT
				user_id,
				score,
				rank,
				rank_inv
			FROM
				$table
			$where
			ORDER BY
				score $order
			LIMIT
				".intval($num)."
		");
		
		$sth->execute();

		$board = array();
		while($row = $sth->fetch()) {
			$board[] = array(
				"user" => $this->uf->prepareUserFromId($row["user_id"]),
				"score" => $row["score"],
				"rank" => $inv ? $row["rank"] : $row["rank_inv"]
			);
		}
		
		return $board;
	}

	public function getApplications () {
		$result = array();
		$qry = $this->db->query("SELECT DISTINCT(app) FROM scores ORDER BY app;");
		while ($row = $qry->fetch()) {
			$result[] = $row[0];
		}
		return $result;
	}
}

