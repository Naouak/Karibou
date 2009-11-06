<?php

class linkshareSubmit extends AppContentModel {

	public function isModifiable($key) {
		if ($this->app->getPermission() == _ADMIN_) {
			return true;
		}
		$query = $this->db->prepare("SELECT reporter FROM linkshare WHERE id=:id");
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
		$query = $this->db->prepare("UPDATE linkshare SET `link`=:link, `title`=:title WHERE id=:id LIMIT 1");
		$query->bindValue(":link", $parameters["link"]);
		$query->bindValue(":title", $parameters["title"]);
		$query->bindValue(":id", intval($key));
		if (!$query->execute()) {
			Debug::kill("Error while updating");
		}
	}

	public function delete ($key) {
		$query = $this->db->prepare("UPDATE linkshare SET `deleted`=1 WHERE id=:id LIMIT 1");
		$query->bindValue(":id", intval($key));
		if (!$query->execute()) {
			Debug::kill("Error while updating");
		}
	}

	public function fillFields($key, &$fields) {
		$query = $this->db->prepare("SELECT `link`, `title` FROM linkshare WHERE id=:id");
		$query->bindValue(":id", intval($key));
		if (!$query->execute()) {
			Debug::kill("Error while filling fields");
		} else {
			$row = $query->fetch();
			$fields["link"]["value"] = $row["link"];
			$fields["title"]["value"] = $row["title"];
			
		}
	}

	public function submit($parameters) {
		// Insérer ici le code pour gérer la réception des données
		$query = $this->db->prepare("INSERT INTO linkshare (`reporter`, `link`, `title` ) VALUES (:reporter,:link,:title)");
		$query->bindValue(":reporter", $this->currentUser->getID());
		$query->bindValue(":link", $parameters["link"]);
		$query->bindValue(":title",$parameters["title"]);
		if (!$query->execute()) {
			Debug::kill("Error while inserting !");
		}
	}

	public function formFields () {
		return array(
			"title" => array("type"=>"text","required" => true, "label" => "Titre du lien"),
			"link" => array("type" => "url" ,"required" => true, "label" => "Lien à partager")
		);
	}
}

?>
