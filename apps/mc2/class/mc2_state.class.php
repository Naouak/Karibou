<?php
/**
 * @copyright 2010 Rémy Sanchez <remy.sanchez@hyperthese.net>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 */

class Mc2State extends Model {
	/**
	 * Returns a JSON containing asked messages of the Minichat, in chronological order
	 */
	public function build() {
		$db = Database::instance();

		// Check that the user has the permissions to read the messages
		$app = $this->appList->getApp($this->appname);
		if ($app->getPermission() < _READ_ONLY_) {
			return;
		}

		$lines = intval($this->args["lines"]);
		if($lines > 100) $lines = 100;

		if(!empty($this->args["types"])) {
			$types = explode("|", urldecode($this->args["types"]));
			foreach($types as $k => $v) {
				$types[$k] = $db->quote($v);
			}
		} else {
			$types = array("'msg'");
		}

		$sql = "SELECT id_auteur, UNIX_TIMESTAMP(time) AS time, post, type FROM minichat WHERE type IN (".implode(", ", $types).") ORDER BY id DESC LIMIT :n";
		$sth = $db->prepare($sql);
		$sth->bindValue(":n", $lines, PDO::PARAM_INT);
		$sth->execute();

		$out = array();
		while(($row = $sth->fetch()) !== false) {
			$out[] = array(
				"post" => $row['post'],
				"time" => $row['time'] * 1000,
				"type" => $row['type'],
				"user_id" => $this->userFactory->prepareUserFromId($row['id_auteur'])->getID()
			);
		}

		$this->userFactory->setUserList();

		foreach($out as $k => $v) {
			$v['userlink'] = userlink(array('noicon' => true, 'showpicture' => true, 'user' => $this->userFactory->prepareUserFromId($v['user_id'])), $this->appList);
			$out[$k] = $v;
		}

		return json_encode(array_reverse($out));
	}
}
