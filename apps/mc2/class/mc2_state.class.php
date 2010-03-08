<?php
class Mc2State extends Model {
	public function build() {
		$db = Database::instance();

		$lines = intval($this->args["lines"]);

		if(!empty($this->args["types"])) {
			$types = explode("|", urldecode($this->args["types"]));
			foreach($types as $k => $v) {
				$types[$k] = $db->quote($v);
			}
		} else {
			$types = array("'msg'");
		}

		$sql = "SELECT * FROM minichat WHERE type IN (".implode(", ", $types).") ORDER BY id DESC LIMIT :n";
		$sth = $db->prepare($sql);
		$sth->bindValue(":n", $lines, PDO::PARAM_INT);
		$sth->execute();

		return json_encode($sth->fetchAll());
	}
}
