<?php

class HomeConfig extends Model {
	public function build() {
		if ($this->currentUser->isLogged()) {
			if ($this->currentUser->getPref("default2")) {
				$this->assign("homeConfig", $this->currentUser->getPref("default2"));
				return;
			} else {
				$this->assign("homeConfig", $GLOBALS['config']['applications']['default']['loggedHome']);
			}
		}
		$this->assign("homeConfig", $GLOBALS['config']['applications']['default']['unloggedHome']);
	}
}

?>
