<?php

class HomeConfig extends Model {
	public function build() {
		if ($this->currentUser->isLogged()) {
			if ($this->currentUser->getPref("default2")) {
				$this->assign("homeConfig", $this->currentUser->getPref("default2"));
				return;
			} else {
				$this->assign("homeConfig", '{"tabs": {"Default": {"name": "Default", "sizes": [25, 48.5, 25], "containers": [["citation_0", "ilsontdit_0", "resetbutton_0", "poll_0", "video_0"], ["minichat_0", "mail_0", "annonce_0"], ["onlineusers_0", "dday_0", "birthday_0", "daytof_0"]]}}, "appIds": {"citation": 0, "ilsontdit": 0, "resetbutton": 0, "poll": 0, "video": 0, "minichat": 0, "mail": 0, "annonce": 0, "onlineusers": 0, "dday": 0, "birthday": 0, "daytof": 0}}');
			}
		}
		$this->assign("homeConfig", '{"tabs": {"Default": {"name": "Default", "sizes": [25, 49.5, 25], "containers": [["login_0", "dday_0", "citation_0"], ["news_0", "annonce_0"], ["onlineusers_0", "daytof_0", "birthday_0"]]}}, "appIds": {"login": 0, "dday": 0, "citation": 0, "news": 0, "annonce": 0, "onlineusers": 0, "birthday": 0, "daytof": 0}}');
	}
}

?>
