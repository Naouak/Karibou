<?php

class SaveHome extends FormModel {
	public function build() {
		if ($this->currentUser->isLogged()) {
			$data = filter_input(INPUT_POST, "home");
			if (strlen($data) > 10) {
				$this->currentUser->setPref("default2", $data);
				$this->currentUser->savePrefs($this->db);
			}
			$stmt = $this->db->prepare("INSERT INTO default_log(user, `date`, msg) VALUES (:user, NOW(), :msg)");
			$stmt->bindValue(":user", $this->currentUser->getId());
			$stmt->bindValue(":msg", "Saved home : $data");
			$stmt->execute();
		}
	}
}

?>
