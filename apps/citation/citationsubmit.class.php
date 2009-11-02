<?php

class CitationSubmit extends AppContentModel {

	public function isModifiable($key) {
		if ($this->app->getPermission() == _ADMIN_) {
			return true;
		}
		$query = $this->db->prepare("SELECT user_id FROM citation WHERE id=:id");
		$query->bindValue(":id", $key);
		if (!$query->execute()) {
			Debug::kill("Error while checking rights");
		} else {
			$row = $query->fetch();
			if ($row[0] == $this->currentUser->getID())
				return true;
		}
		return false;
	}

	public function modify ($key, $parameters) {
		$query = $this->db->prepare("UPDATE citation SET `citation`=:citation WHERE id=:id LIMIT 1");
		$query->bindValue(":citation", $parameters["citation"]);
		$query->bindValue(":id", intval($key));
		if (!$query->execute()) {
			Debug::kill("Error while updating");
		}
	}

	public function delete ($key) {
		$query = $this->db->prepare("UPDATE citation SET `deleted`=1 WHERE id=:id LIMIT 1");
		$query->bindValue(":id", intval($key));
		if (!$query->execute()) {
			Debug::kill("Error while updating");
		}
	}

	public function fillFields($key, &$fields) {
		$query = $this->db->prepare("SELECT `citation` FROM citation WHERE id=:id");
		$query->bindValue(":id", intval($key));
		if (!$query->execute()) {
			Debug::kill("Error while filling fields");
		} else {
			$row = $query->fetch();
			$fields["citation"]["value"] = $row["citation"];
		}
	}

	public function submit($parameters) {
		$query = $this->db->prepare("INSERT INTO citation(user_id, citation, datetime) VALUES (:user, :citation, NOW());");
		$query->bindValue(":user", $this->currentUser->getID());
		$query->bindValue(":citation", $parameters["citation"]);
		if (!$query->execute()) {
			Debug::kill("Error while inserting !");
		}
	}

	public function formFields () {
		return array("citation" => array("type" => "textarea" ,"required" => true, "columns" => 30, "rows" => 8));
	}
}

?>
