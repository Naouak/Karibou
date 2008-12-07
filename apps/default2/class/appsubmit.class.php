<?php

class AppSubmit extends FormModel {
	public function build() {
		$miniappName = $_POST["miniapp"];
		if ($miniappName == "")
			return;
		$miniapp = MiniAppFactory::buildApplication($miniappName);
		if ($miniapp->getSubmitModel() == "")
			return;
		$app = $this->appList->getApp($miniappName);
		if ($app->getPermission() < _SELF_WRITE_)
			return;
		$modelName = $miniapp->getSubmitModel();
		$model = new $modelName($this->db, $this->currentUser);
		$model->build();
	}
}

?>
