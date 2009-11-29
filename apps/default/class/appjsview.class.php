<?php

class AppJSView extends Model
{
	function build()
	{
		if (isset($this->args['miniapp']))
		{
			$miniapp = MiniAppFactory::buildApplication($this->args['miniapp']);
			$app = $this->appList->getApp($this->args['miniapp']);
			if ($miniapp->getJSView() != "") {
				$app->addView($miniapp->getJSView(), "JS_" . $miniapp->getAppName());
				$this->assign("hasJS", true);
			} else {
				$this->assign("hasJS", false);
				if ($miniapp->getAutorefresh() > 0) {
					$this->assign("autorefresh", $miniapp->getAutorefresh());
				}
			}
			$submitModelName = $miniapp->getSubmitModel();
			if ($submitModelName != "") {
				$submitModel = new $submitModelName($this->db, $this->currentUser, $miniapp->getAppName(), $app);
				$this->assign("submitFields", json_encode($submitModel->formFields()));
			} else {
				$this->assign("submitFields", "");
			}
			$configModelName = $miniapp->getConfigModel();
			if ($configModelName != "") {
				$configModel = new $configModelName($this->db, $this->currentUser, $miniapp->getAppName(), $app);
				$this->assign("configFields", json_encode($configModel->formFields()));
			} else {
				$this->assign("configFields", "");
			}
			$this->assign("appName", $miniapp->getAppName());
			ThemeManager::instance()->flushApplications();
			ThemeManager::instance()->addApplication($miniapp->getAppName());
			$this->assign("base_url", $GLOBALS["config"]["base_url"]);
			$this->assign("cssfiles", ThemeManager::instance()->getCSSList());
		}
	}
}
?>
