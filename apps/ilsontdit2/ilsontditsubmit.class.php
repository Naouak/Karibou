<?php

class IlsontditSubmit extends AppContentModel {

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
