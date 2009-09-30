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

	public static function initialize($db, $uf) {
		self::$instance = new ScoreManager($db, $uf);

		// Shall we update the scores_valid view ?
		if(is_readable(KARIBOU_CACHE_DIR . '/score_start_time')) {
			$score_start_time = intval(unserialize(implode("", file(KARIBOU_CACHE_DIR . '/score_start_time'))));
		} else {
			$score_start_time = 0;
		}

		if($score_start_time != $GLOBALS["config"]["scores"]["start"]) {
			$score_start_time = $GLOBALS["config"]["scores"]["start"];

			try {
				$sth = $db->prepare("
					ALTER VIEW
						scores_valid
					AS
						SELECT
							id,
							user_id,
							score,
							app
						FROM
							scores
						WHERE
							date >= FROM_UNIXTIME(:time)
				");
				$sth->bindValue(":time", $score_start_time, PDO::PARAM_INT);
				$sth->execute();

				trigger_error('Updated the scores_valid view', E_USER_NOTICE);

				$fh = fopen(KARIBOU_CACHE_DIR . '/score_start_time', 'w');
				fwrite($fh, serialize($score_start_time));
				fclose($fh);
			} catch(Exception $ex) {
				trigger_error('Unable to update the valid scores view: ' . $ex->getMessage(), E_USER_WARNING);
			}
		}
	}

	public static function addScoreToUser(User $user, $score, $app = "none") {
		self::getInstance()->addScoreToUser($user, $score, $app);
	}

	public static function getScoreOf(User $user, $app = null) {
		return self::getInstance()->getScoreOf($user, $app);
	}

	public static function getRankOf(User $user, $app = null) {
		return self::getInstance()->getRankOf($user, $app);
	}

	public static function getInvRankOf(User $user, $app = null) {
		return self::getInstance()->getInvRankOf($user, $app);
	}

	public static function getScoreBoard($num, $inv = false, $app = null) {
		return self::getInstance()->getScoreBoard($num, $inv, $app);
	}

	public static function stealScoreFromUser($thief, $victim, $score, $app = "none") {
		return self::getInstance()->stealScoreFromUser($thief, $victim, $score, $app);
	}

	public static function getNumberOfPlayers() {
		return self::getInstance()->getNumberOfPlayers();
	}
}
