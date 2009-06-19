<?php
class ScoreManager {
	protected $db;

	public function __construct($db) {
		$this->db = $db;
	}

	public function addScoreToUser(User $user, $score) {
		$query =
			"INSERT INTO scores (user_id, score) ".
			"VALUES (:user, :score) ".
			"ON DUPLICATE KEY UPDATE ".
			"score = score + :score ";
		$sth = $this->db->prepare($query);
		$sth->bindValue(":score", $score);
		$sth->bindValue(":user", $user->getID());
		$sth->execute();
	}

	public function getScoreOf(User $user) {
		$query =
			"SELECT SUM(score) AS score".
			"FROM scores ".
			"WHERE user_id = :user ";
		$sth = $this->db->prepare($query);
		$sth->bindValue(":user", $user->getID());
		$sth->execute();

		return $sth->fetchColumn(0);
	}
}