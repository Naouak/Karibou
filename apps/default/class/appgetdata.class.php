<?php

class AppGetData extends Model {
	public function build () {
		if (!isset($this->args['miniapp'])) {
			return "";
		}
		$miniappName = $this->args['miniapp'];
		$miniapp = MiniAppFactory::buildApplication($miniappName);
		if ($miniapp->getSubmitModel() == "") {
			return "";
		} else {
			$submitModelName = $miniapp->getSubmitModel();
			$app = $this->appList->getApp($miniappName);
			$submitModel = new $submitModelName($this->db, $this->currentUser, $miniappName, $app);
			$key = filter_input(INPUT_POST, "key");
			if (!isset($key))
				throw new Exception("Missing 'key' parameter");
			$submitArray = array();
			if ($submitModel->isModifiable($key)) {
				foreach($submitModel->formFields() as $formKey => $formValue) {
					$submitArray[$formKey] = $formValue;
				}
				$submitModel->fillFields($key, $submitArray);
				return json_encode($submitArray);
			} else {
				throw new Exception("Unmodifiable 'key' $key");
			}
		}
	}
}

?>
