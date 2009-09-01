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

		if($this->args["from"] == "top") {
			$order = "DESC";
		} else {
			$order = "ASC";
		}

		$limit = intval($this->args["number"]);

		$sth = $this->db->prepare("
			SELECT
				score,
				user_id,
				rank,
				rank_inv
			FROM
				scores_total
			ORDER BY
				score $order
			LIMIT
				$limit
		");

		$sth->bindValue(":from", $GLOBALS["config"]["scores"]["start"]);

		$result = $sth->execute();

		$scores = array();
		if($result) while($row = $sth->fetch()) {
			$scores[] = array(
				"score" => $row["score"],
				"user" => $this->userFactory->prepareUserFromId($row["user_id"]),
				"rank" => ($this->args["from"] == "top") ? $row["rank"] : $row["rank_inv"]
			);
		}

		$this->assign("selfscore", ScoreFactory::getScoreOf($this->userFactory->getCurrentUser()));
		$this->assign("selfrank", ScoreFactory::getRankOf($this->userFactory->getCurrentUser()));
		$this->assign("selfrankinv", ScoreFactory::getInvRankOf($this->userFactory->getCurrentUser()));
		$this->assign("scores", $scores);
		$this->assign("islogged", $this->currentUser->islogged());
	}
}
