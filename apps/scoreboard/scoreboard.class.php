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
				SUM(score) AS score,
				user_id
			FROM
				scores
			WHERE
				date >= FROM_UNIXTIME(:from)
			GROUP BY
				user_id
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
				"user" => $this->userFactory->prepareUserFromId($row["user_id"])
			);
		}

		$this->assign("selfscore", ScoreFactory::getScoreOf($this->userFactory->getCurrentUser()));
		$this->assign("scores", $scores);
		$this->assign("islogged", $this->currentUser->islogged());
	}
}
