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
		self::$instance = new ScoreManager($db);
	}

	public static function addScoreToUser(User $user, $score) {
		self::getInstance()->addScoreToUser($user, $score);
	}

	public static function getScoreOf(User $user) {
		return sefl::getInstance()->getScoreOf($user);
	}
}
?>
