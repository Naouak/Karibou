<?php

class AppDeleteModel extends FormModel {
	public function build () {
		if (!isset($_POST['miniapp'])) {
			print("bad1");
			return;
		}
		if (!isset($_POST['key'])) {
			print("bad2");
			return;
		}
		$miniappName = filter_input(INPUT_POST, 'miniapp');
		$miniapp = MiniAppFactory::buildApplication($miniappName);
		if ($miniapp->getSubmitModel() == "")
			return;
		$app = $this->appList->getApp($miniappName);
		$modelName = $miniapp->getSubmitModel();
		$model = new $modelName($this->db, $this->currentUser, $miniappName, $app);
		$key = filter_input(INPUT_POST, 'key');
		if ($model->canDelete($key)) {
			$model->delete($key);
			$p = new Pantie();
			$p->throwEvent("default-*-$miniappName", "delete");
		} else {
			print("vtff...");
		}
	}
}

?>
