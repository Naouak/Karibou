<?php

class DefaultModel extends Model {
	public function build() {
		$this->assign("apps", MiniAppFactory::listApplications());
		$this->assign("birthday", MiniAppFactory::buildApplication("birthday2"));
		$this->assign("karibou_base_url", $GLOBALS['config']['base_url']);
	}
}

?>