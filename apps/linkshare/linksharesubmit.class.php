<?php

class linkshareSubmit extends AppContentModel {

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
		return array("link" => array("type" => "url" ,"required" => true, "label" => "Lien à partager : "), "title" => array("type"=>"text","required" => true, "label" => "titre du lien"));
	}
}

?>
