<?php

abstract class DefaultFormModel {

	protected $db;

	protected $currentUser;

	protected $appName;

	protected $app;

	public function __construct(PDO $db, CurrentUser $currentUser, $appName, $app) {
		$this->db = $db;
		$this->currentUser = $currentUser;
		$this->appName = $appName;
		$this->app = $app;
	}

	public function build() {
		// 1) lire les parametres en post
		// 2) les vérifier
		// 3) les stocker dans un tableau
		// 4) appeler la fonction $this->submit($params);
		$params = array();
		foreach ($this->formFields() as $fieldID => $fieldObj) {
			$value = filter_input(INPUT_POST, $fieldID);
			if($value != "") {
				if ($fieldObj["type"] == "span")
					return;
				$value = null;
				if ($fieldObj["type"] == "text") {
					$value = filter_input(INPUT_POST, $fieldID, FILTER_SANITIZE_STRING);
				} else if ($fieldObj["type"] == "password") {
					$krypt = new Krypt();
					$value = $krypt->decrypt(filter_input(INPUT_POST, $fieldID));
				} else if ($fieldObj["type"] == "textarea") {
					$value = filter_input(INPUT_POST, $fieldID, FILTER_SANITIZE_STRING);
				} else if ($fieldObj["type"] == "url") {
					$value = filter_input(INPUT_POST, $fieldID, FILTER_VALIDATE_URL);
					if ($value === false)
						throw new Exception("Invalid field value");
				} else if ($fieldObj["type"] == "date") {
					$value = $_POST[$fieldID];
					if (preg_match('/^(\d\d)\/(\d\d)\/(\d\d\d\d)$/', $value, $result)) {
						$day = $result[1];
						$month = $result[2];
						$year = $result[3];
						$value = "$year-$month-$day";
					} else {
						throw new Exception("Invalid field value");
					}
				} else if ($fieldObj["type"] == "file") {
					$value = $_FILES[$fieldID];
				} else if ($fieldObj["type"] == "float") {
					$value = filter_input(INPUT_POST, $fieldID, FILTER_VALIDATE_FLOAT);
					$value = floatval($value);
					if ($value === false)
						throw new Exception("Invalid field value");
					if (array_key_exists("max", $fieldObj) && $value>$fieldObj["max"])
						throw new Exception("Invalid field value");
					if (array_key_exists("min", $fieldObj) && $value<$fieldObj["min"])
						throw new Exception("Invalid field value");
				} else if ($fieldObj["type"] == "int") {
					$value = filter_input(INPUT_POST, $fieldID, FILTER_VALIDATE_INT);
					$value = intval($value);
					if ($value === false)
						throw new Exception("Invalid field value");
					if (array_key_exists("max", $fieldObj) && $value>$fieldObj["max"])
						throw new Exception("Invalid field value");
					if (array_key_exists("min", $fieldObj) && $value<$fieldObj["min"])
						throw new Exception("Invalid field value");
				} else if ($fieldObj["type"] == "bool") {
					$value = filter_input(INPUT_POST, $fieldID, FILTER_VALIDATE_BOOLEAN);
					if ($value === null)
						throw new Exception("Invalid field value : " . $_POST[$fieldID]);
					$value = ($value == "true");
				} else if ($fieldObj["type"] == "enum") {
					$value = filter_input(INPUT_POST, $fieldID);
					if (!array_key_exists($value, $fieldObj["values"]))
						throw new Exception("Invalid field value");
				} else {
					throw new Exception("Unsupported field type");
				}
			}
			if ((array_key_exists("required", $fieldObj) && ($fieldObj["required"] == true)) && ($value == "")) {
				throw new Exception("Missing required value for field $fieldID");
			} else {
				$params[$fieldID] = $value;
			}
		}
		$this->submit($params);
	}

	public abstract function formFields();

	public abstract function submit($parameters);
}

abstract class AppContentModel extends DefaultFormModel {

}

abstract class AppConfigModel extends DefaultFormModel {
	public function submit($parameters) {
		if (!isset($_POST["miniapp"]))
			throw new Exception("Missing miniapp parameter");
		if (preg_match('/^([a-zA-Z0-9\-_]*)_(\d*)$/i', $_POST['miniapp'], $result)) {
			$this->currentUser->setPref($_POST["miniapp"], $parameters);
			$this->currentUser->savePrefs($this->db);
		}
	}
}

?>
