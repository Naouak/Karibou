<?php

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
