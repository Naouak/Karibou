<?php

class HomeConfig extends Model {
	public function build() {
		if ($this->currentUser->isLogged()) {
			if ($this->currentUser->getPref("default2")) {
				$this->assign("homeConfig", $this->currentUser->getPref("default2"));
				return;
			}
		}
		$this->assign("homeConfig", '{"tabs": {"Default": {"name": "Default", "sizes": [33, 33, 33], "containers": [["citation2_0", "ilsontdit2_0"], ["onlineusers2_0"], ["dday2_0", "birthday2_0"]]}}, "appIds": {"citation2": 0, "dday2": 0, "birthday2": 0, "ilsontdit2": 0, "onlineusers2": 0}}');
	}
}

?>
