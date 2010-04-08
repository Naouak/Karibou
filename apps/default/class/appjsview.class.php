<?php

class AppJSView extends Model
{
	function build()
	{
		$apps = array();
		ThemeManager::instance()->flushApplications();
		foreach ($this->args as $key => $miniappName) {
			if (substr($key, 0, 7) != 'miniapp')
				continue;
			$miniapp = MiniAppFactory::buildApplication($miniappName);
			$app = $this->appList->getApp($miniappName);

			// Do not ask
			if(!is_object($miniapp)) continue;
			
			if ($miniapp->getJSView() != "") {
				$app->addView($miniapp->getJSView(), "JS_" . $miniapp->getAppName());
				$hasJS = true;
			} else {
				$hasJS = false;
			}
			$submitModelName = $miniapp->getSubmitModel();
			if ($submitModelName != "") {
				$submitModel = new $submitModelName($this->db, $this->currentUser, $miniapp->getAppName(), $app);
				$submitFields = json_encode($submitModel->formFields());
			} else {
				$submitFields = "";
			}
			$configModelName = $miniapp->getConfigModel();
			if ($configModelName != "") {
				$configModel = new $configModelName($this->db, $this->currentUser, $miniapp->getAppName(), $app);
				$configFields = json_encode($configModel->formFields());
			} else {
				$configFields = "";
			}
			$appName = $miniapp->getAppName();
			ThemeManager::instance()->addApplication($appName);
			$apps[] = array("hasJS" => $hasJS, "appName" => $appName, "configFields" => $configFields, "submitFields" => $submitFields);
		}
		$this->assign("apps", $apps);
		$this->assign("base_url", $GLOBALS["config"]["base_url"]);
		$this->assign("cssfiles", array_unique(ThemeManager::instance()->getCSSList()));
	}
}
?>
