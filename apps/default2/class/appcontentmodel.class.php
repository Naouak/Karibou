<?php

abstract class AppContentModel {

	protected $db;

	protected $currentUser;

	public function __construct(PDO $db, CurrentUser $currentUser) {
		$this->db = $db;
		$this->currentUser = $currentUser;
	}

	public function build() {
		/*return array("author" => array("type" => "text", "required" => true, "label" => "Qui l'a dit : "),
		  "group" => array("type" => "text", "required" => true, "label" => "Promo : "),
		  "quote" => array("type" => "textarea" ,"required" => true, "label" => "Citation : ", "columns" => 30, "rows" => 8));*/
		// 1) lire les parametres en post
		// 2) les vérifier
		// 3) les stocker dans un tableau
		// 4) appeler la fonction $this->submit($params);
		$params = array();
		foreach ($this->formFields() as $fieldID => $fieldObj) {
			if ($fieldObj["type"] == "span")
				return;
			$value = null;
			if ($fieldObj["type"] == "text") {
				$value = filter_input(INPUT_POST, $fieldID, FILTER_SANITIZE_STRING);
			} else if ($fieldObj["type"] == "textarea") {
				$value = filter_input(INPUT_POST, $fieldID, FILTER_SANITIZE_STRING);
			} else {
				throw new Exception("Unsupported field type");
			}
			if ((array_key_exists("required", $fieldObj) && ($fieldObj["required"] == true)) && ($value == "")) {
				throw new Exception("Missing required value");
			} else {
				$params[$fieldID] = $value;
			}
		}
		$this->submit($params);
	}

	public abstract function formFields();

	public abstract function submit($parameters);
}

?>
