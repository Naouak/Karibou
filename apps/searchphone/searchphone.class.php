<?php

class SearchPhone extends Model {
	public function build() {
		// number has been automatically filtered, remember the config.xml settings...
		$number = $this->args['number'];

		$annudb = $GLOBALS['config']['bdd']["frameworkdb"];

		$sql = $this->db->prepare("SELECT u.id, pp.number 
			FROM $annudb.users u 
			LEFT JOIN $annudb.profile_phone pp ON pp.profile_id = u.profile_id 
			WHERE $annudb.filterNumeric(pp.number) LIKE :search");
		$sql->bindValue(':search', '%' . $number . '%');
		$sql->execute();
		$result = array();
		while ($row = $sql->fetch()) {
			$u = $this->userFactory->prepareUserFromId($row[0]);
			$result[] = array("user" => $u, "number" => $row[1]);
		}
		$this->assign("results", $result);
	}
}

?>
