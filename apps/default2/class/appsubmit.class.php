<?php

class AppSubmit extends FormModel {
	public function build() {
		print("AppSubmit::build() 1");
		$miniappName = $_POST["miniapp"];
		if ($miniappName == "")
			return;
		print("AppSubmit::build() 2");
		$miniapp = MiniAppFactory::buildApplication($miniappName);
		if ($miniapp->getSubmitModel() == "")
			return;
		print("AppSubmit::build() 3");
		$app = $this->appList->getApp($miniappName);
		if ($app->getPermission() < _SELF_WRITE_)
			return;
		print("AppSubmit::build() 4");
		$modelName = $miniapp->getSubmitModel();
		$model = new $modelName($this->db, $this->currentUser);
		$model->build();
		print("AppSubmit::build() 5");
	}
}

?>
