<?php

class AppConfig extends FormModel {
	public function build() {
		$miniappName = "";
		$miniappId = "";
		if (preg_match('/^([a-zA-Z0-9\-_]*)_(\d*)$/i', $_POST['miniapp'], $result)) {
			$miniappName = $result[1];
			$miniappId = $result[2];
		} else {
			throw new Exception("Invalid miniapp parameter");
		}
		if ($miniappName == "")
			return;
		$miniapp = MiniAppFactory::buildApplication($miniappName);
		if ($miniapp->getConfigModel() == "")
			return;
		$app = $this->appList->getApp($miniappName);
		if ($app->getPermission() < _SELF_WRITE_)
			return;
		$modelName = $miniapp->getConfigModel();
		$model = new $modelName($this->db, $this->currentUser, $miniappName, $app);
		$model->build();
	}
}

?>
