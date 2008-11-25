<?php

class AppJSView extends Model
{
	function build()
	{
		if (isset($this->args['miniapp']))
		{
			$miniapp = MiniAppFactory::buildApplication($this->args['miniapp']);
			if ($miniapp->getJSView() != "") {
				$this->appList->getApp($this->args['miniapp'])->addView($miniapp->getJSView(), "JS_" . $miniapp->getAppName());
				$this->assign("hasJS", true);
			} else {
				$this->assign("hasJS", false);
			}
			$this->assign("appName", $miniapp->getAppName());
		}
	}
}
?>