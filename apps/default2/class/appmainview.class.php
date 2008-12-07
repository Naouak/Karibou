<?php

class AppMainView extends Model
{
	function build()
	{
		if (isset($this->args['miniapp']))
		{
			$miniapp = MiniAppFactory::buildApplication($this->args['miniapp']);
			$app = $this->appList->getApp($this->args['miniapp']);
			$app->addView($miniapp->getMainView(), $miniapp->getAppName());
			if ($miniapp->getSubmitModel() != "") {
				$this->assign("canSubmit", ($app->getPermission() >= _SELF_WRITE_));
			} else {
				$this->assign("canSubmit", false);
			}
			$this->assign("appName", $miniapp->getAppName());
			$this->assign("appTitle", $miniapp->getName("fr"));
		}
	}
}
?>
