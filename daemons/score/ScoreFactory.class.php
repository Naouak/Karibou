<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ScoreFactory
 *
 * @author remy
 */
class ScoreFactory {
	static $instance;

	private function __construct() {	
	}

	public static function getInstance() {
		if(!(self::$instance instanceof ScoreManager)) {
			throw new Exception("The score instance is not created !");
		}
		return self::$instance;
	}

	public static function initialize($db) {
		try {
			$db->query("CREATE VIEW scores_total AS SELECT user_id, SUM(score) AS score, app FROM scores GROUP BY user_id, app");
		} catch (PDOException $e) {
			// do nothing
		}

		self::$instance = new ScoreManager($db);
	}

	public static function addScoreToUser(User $user, $score, $app = "none") {
		self::getInstance()->addScoreToUser($user, $score, $app);
	}

	public static function getScoreOf(User $user, $app = null) {
		return self::getInstance()->getScoreOf($user, $app);
	}
}
?>
