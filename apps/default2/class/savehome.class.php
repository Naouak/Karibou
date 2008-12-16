<?php

class SaveHome extends FormModel {
	public function build() {
		if ($this->currentUser->isLogged()) {
			$data = filter_input(INPUT_POST, "home");
			$this->currentUser->setPref("default2", $data);
			$this->currentUser->savePrefs($this->db);
		}
	}
}

?>
