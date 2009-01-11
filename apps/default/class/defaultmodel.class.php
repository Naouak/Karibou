<?php

class DefaultModel extends Model {
	public function build() {
		$this->assign("lang", $this->currentUser->getPref("lang"));
		$appNames = MiniAppFactory::listApplications();
		$appArray = Array();
		foreach($appNames as $appName) {
			$appArray[$appName] = MiniAppFactory::buildApplication($appName);
		}
		$this->assign("apps", $appArray);
		$this->assign("karibou_base_url", $GLOBALS['config']['base_url']);
	}
}

?>
