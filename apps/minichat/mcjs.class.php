<?php

class MCJS extends Model {
	public function build() {
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$this->assign("refreshInterval", $config["refresh"]["small"]);
	}
}
?>
