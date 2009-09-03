<?php
class ScoreManager {
	protected $db;

	public function __construct($db, $uf) {
		$this->db = $db;
		$this->uf = $uf;
	}

	public function addScoreToUser(User $user, $score, $app = "none") {
		$query =
			"INSERT INTO scores (user_id, score, app) ".
			"VALUES (:user, :score, :app) ";
		$sth = $this->db->prepare($query);
		$sth->bindValue(":score", $score);
		$sth->bindValue(":user", $user->getID());
		$sth->bindValue(":app", $app);
		$sth->execute();
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
}
