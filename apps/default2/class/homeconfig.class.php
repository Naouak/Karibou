<?php

class HomeConfig extends Model {
	public function build() {
		if ($this->currentUser->isLogged()) {
			if ($this->currentUser->getPref("default2")) {
				$this->assign("homeConfig", $this->currentUser->getPref("default2"));
				return;
			}
		}
		$this->assign("homeConfig",'{"tabs": [{"name": "default", "id": 0, "sizes": [33, 33, 33], "containers": [["citation2_0"], [], []]}]}'); 
	}
}

?>
