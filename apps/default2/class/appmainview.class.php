<?php

class AppMainView extends Model
{
	function build()
	{
		if (isset($this->args['miniapp']))
		{
			$miniapp = MiniAppFactory::buildApplication($this->args['miniapp']);
			$this->appList->getApp($this->args['miniapp'])->addView($miniapp->getMainView(), $miniapp->getAppName());
			$this->assign("appName", $miniapp->getAppName());
			$this->assign("appTitle", $miniapp->getName("fr"));
		}
	}
}
?>
