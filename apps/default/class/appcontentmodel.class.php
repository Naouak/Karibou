<?php

abstract class AppContentModel extends DefaultFormModel {

	public function isModifiable($key) {
		return false;
	}

	public function modify($key, $params) {
		throw new Exception("Unmodifiable content");
	}

	public function canDelete($key) {
		return $this->isModifiable($key);
	}

	public function delete($key) {
		throw new Exception("Undeletable content");
	}

	public function fillFields($key, $fields) {
		throw new Exception("Unmodifiable content");
	}
	
	public function validated ($params) {
		if (array_key_exists("__modified_key", $_POST)) {
			$key = $_POST["__modified_key"];
			if ($this->isModifiable($key)) {
				$this->modify($key, $params);
				$p = new Pantie();
				$p->throwEvent("default-*-" . $this->appName, "modify");
			}
		} else {
			$this->submit($params);
			$p = new Pantie();
			$p->throwEvent("default-*-" . $this->appName, "submit");
		}
	}
}

?>
