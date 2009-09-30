<?php
/**
 * @copyright 2009 Rémy Sanchez <remy.sanchez@hyperthese.net>
 * @licence GPL v2
 *
 * @package applications
 */

class Scoreboard extends Model {
	public function build() {
		if(!isset($this->args["from"])) $this->args["from"] = "top";
		if(!isset($this->args["number"])) $this->args["number"] = 10;
		if(!isset($this->args["hide"])) $this->args["hide"] = false;

		$scores = ScoreFactory::getScoreBoard($this->args["number"], $this->args["from"] == "top");
		
		$this->assign("selfscore", ScoreFactory::getScoreOf($this->currentUser));
		$this->assign("selfrank", ScoreFactory::getRankOf($this->currentUser));
		$this->assign("selfrankinv", ScoreFactory::getInvRankOf($this->currentUser));
		$this->assign("scores", $scores);
		$this->assign("islogged", $this->currentUser->islogged());
		$this->assign("hide", $this->args["hide"]);
		$this->assign("players", ScoreFactory::getNumberOfPlayers());
	}
}
