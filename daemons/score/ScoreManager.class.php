<?php
class ScoreManager {
	protected $db;

	public function __construct($db) {
		$this->db = $db;
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
				"SELECT SUM(score) AS score ".
				"FROM scores_total ".
				"WHERE user_id = :user ";
			$sth = $this->db->prepare($query);
			$sth->bindValue(":user", $user->getID());
			$sth->execute();

			return $sth->fetchColumn(0);
		} else {
			$query =
				"SELECT SUM(score) AS score ".
				"FROM scores_total ".
				"WHERE user_id = :user ".
				"AND app = :app";
			$sth = $this->db->prepare($query);
			$sth->bindValue(":user", $user->getID());
			$sth->bindValue(":app", $app);
			$sth->execute();

			return $sth->fetchColumn(0);
		}
	}
}