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
		if ($miniapp->getConfigModel() == "")
			$this->assign("config", "");
		else
			$this->assign("config", json_encode($this->currentUser->getPref($miniappName . '_' . $miniappId)));
	}
}

?>
