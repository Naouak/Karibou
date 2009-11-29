<?php

class AppModifyData extends Model {
	public function build () {
		if (!isset($this->args['miniapp'])) {
			return "";
		}
		$miniappName = $this->args['miniapp'];
		$miniapp = MiniAppFactory::buildApplication($miniappName);
		if ($miniapp->getSubmitModel() == "")
			return "";
		$app = $this->appList->getApp($miniappName);
		if (!isset($_POST["__modified_key"]))
			throw new Exception("Missing parameter");
		$modelName = $miniapp->getSubmitModel();
		$model = new $modelName($this->db, $this->currentUser, $miniappName, $app);
		$model->build();
		return "";
	}
}

?>
