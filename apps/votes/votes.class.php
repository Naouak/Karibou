<?php
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 * @copyright 2010 Pierre Ducroquet <pinaraf@pinaraf.info>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *-
 * @package applications
 **/


/**
 * Class Votes
 *
 * @package applications
 */
class Votes extends Model {
	public function build() {
		if (abs($this->args["votes"]) == 1) {
			$sql = $this->db->prepare("INSERT INTO votes (key_id,user,vote) VALUES (:id,:user,:vote)");
			$sql->bindValue(":id", $this->args["id"]);
			$sql->bindValue(":user", $this->currentUser->getId());
			$sql->bindValue(":vote", $this->args["votes"]);
			try {
				if ($sql->execute()) {
					$this->assign("status", "ok");
					// This system is quite hacky, but it should work.
					$sql = $this->db->prepare("SELECT name FROM combox WHERE id=:id");
					$sql->bindValue(":id", $this->args["id"]);
					if ($sql->execute()) {
						$row = $sql->fetch();
						if (preg_match('/([^-]*)-(.*)/', $row[0], $result)) {
							$p = new Pantie();
							$p->throwEvent("default-*-" . $result[1], "vote");
						}
					}
				}
				else{
					$this->assign("status", "insert error");
				}
			} catch (PDOException $e) {
				$this->assign("status", "SQL error");
			}
		} else {
			$this->assign("status", "codejacking");
		}
	}
}
