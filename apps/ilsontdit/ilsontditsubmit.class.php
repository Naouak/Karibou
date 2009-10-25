<?php

class IlsontditSubmit extends AppContentModel {

	public function isModifiable($key) {
		if ($this->app->getPermission() == _ADMIN_) {
			return true;
		}
		$query = $this->db->prepare("SELECT reporter FROM ilsontdit WHERE id=:id");
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
		$query = $this->db->prepare("UPDATE ilsontdit SET `who`=:who, `group`=:group, `message`=:msg WHERE id=:id LIMIT 1");
		$query->bindValue(":group", $parameters["group"]);
		$query->bindValue(":who", $parameters["author"]);
		$query->bindValue(":msg", $parameters["quote"]);
		$query->bindValue(":id", intval($key));
		if (!$query->execute()) {
			Debug::kill("Error while updating");
		}
	}

	public function delete ($key) {
		print("Calling delete($key)");
		$query = $this->db->prepare("UPDATE ilsontdit SET `deleted`=1 WHERE id=:id LIMIT 1");
		$query->bindValue(":id", intval($key));
		if (!$query->execute()) {
			Debug::kill("Error while updating");
		}
	}

	public function fillFields($key, &$fields) {
		$query = $this->db->prepare("SELECT `who`, `group`, `message` FROM ilsontdit WHERE id=:id");
		$query->bindValue(":id", intval($key));
		if (!$query->execute()) {
			Debug::kill("Error while filling fields");
		} else {
			$row = $query->fetch();
			$fields["author"]["value"] = $row["who"];
			$fields["quote"]["value"] = $row["message"];
			$fields["group"]["value"] = $row["group"];
		}
	}

	public function submit($parameters) {
		// Insérer ici le code pour gérer la réception des données
		$query = $this->db->prepare("INSERT INTO ilsontdit (`reporter`, `group`, `who`, `message`, `date_report`) VALUES (:reporter, :group, :who, :msg, NOW())");
		$query->bindValue(":reporter", $this->currentUser->getID());
		$query->bindValue(":group", $parameters["group"]);
		$query->bindValue(":who", $parameters["author"]);
		$query->bindValue(":msg", $parameters["quote"]);
		if (!$query->execute()) {
			Debug::kill("Error while inserting !");
		}
	}

	public function formFields () {
		return array("author" => array("type" => "text", "required" => true, "label" => "Qui l'a dit : "),
			"group" => array("type" => "text", "required" => true, "label" => "Promo : "),
			"quote" => array("type" => "textarea" ,"required" => true, "label" => "Citation : ", "columns" => 30, "rows" => 8));
	}
}

?>
