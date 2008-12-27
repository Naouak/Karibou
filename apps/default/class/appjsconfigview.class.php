<?php

class AppJSConfigView extends Model {
	public function build() {
		$miniappName = "";
		$miniappId = "";
		if (preg_match('/^([a-zA-Z0-9\-_]*)_(\d*)$/i', $this->args['miniapp'], $result)) {
			$miniappName = $result[1];
			$miniappId = $result[2];
		} else {
			throw new Exception("Invalid miniapp parameter");
		}
		$miniapp = MiniAppFactory::buildApplication($miniappName);
		if ($miniapp->getConfigModel() == "") {
			$this->assign("config", "");
		} else {
			$conf = $this->currentUser->getPref($miniappName . '_' . $miniappId);
			if (($conf == "") || ($conf === false)) {
				$confModelName = $miniapp->getConfigModel();
				$app = $this->appList->getApp($miniappName);
				$confModel = new $confModelName($this->db, $this->currentUser, $miniappName, $app); 
				$confArray = array();
				foreach ($confModel->formFields() as $key => $value) {
					if (array_key_exists("value", $value))
						$confArray[$key] = $value["value"];
				}
				$this->assign("config", json_encode($confArray));
			} else {
				$this->assign("config", json_encode($conf));
			}
		}
	}
}

?>
