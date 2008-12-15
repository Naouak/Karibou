<?php

class AppMainView extends Model
{
	function build()
	{
		if (isset($this->args['miniapp']))
		{
			if (preg_match('/^([a-zA-Z0-9\-_]*)_(\d*)$/i', $this->args['miniapp'], $result)) {
				$miniappName = $result[1];
				$miniappId = $result[2];
				$miniapp = MiniAppFactory::buildApplication($miniappName);
				$app = $this->appList->getApp($miniappName);
				$prefs = array();
				if ($this->currentUser->isLogged()) {
					$prefs = $this->currentUser->getPref($this->args["miniapp"]);
					if ($prefs === FALSE)
						$prefs = array();
				}
				$app->addView($miniapp->getMainView(), $miniapp->getAppName(), $prefs);
				if ($miniapp->getSubmitModel() != "") {
					$this->assign("canSubmit", ($app->getPermission() >= _SELF_WRITE_));
				} else {
					$this->assign("canSubmit", false);
				}
				if ($miniapp->getConfigModel() != "") {
					$this->assign("canConfig", ($app->getPermission() >= _SELF_WRITE_));
				} else {
					$this->assign("canConfig", false);
				}
				$this->assign("appName", $miniapp->getAppName());
				$this->assign("appTitle", $miniapp->getName("fr"));
			}
		}
	}
}
?>
