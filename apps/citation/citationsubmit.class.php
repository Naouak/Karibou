<?php

class CitationSubmit extends AppContentModel {

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
