<?php

class HomeConfig extends Model {
	public function build() {
		if ($this->currentUser->isLogged()) {
			if ($this->currentUser->getPref("default2")) {
				$this->assign("homeConfig", $this->currentUser->getPref("default2"));
				return;
			}
		}
		$this->assign("homeConfig", '{"tabs": {"Default": {"name": "Default", "sizes": [33, 33, 33], "containers": [["citation_0", "ilsontdit_0"], ["onlineusers_0"], ["dday_0", "birthday_0"]]}}, "appIds": {"citation": 0, "dday": 0, "birthday": 0, "ilsontdit": 0, "onlineusers": 0}}');
	}
}

?>
