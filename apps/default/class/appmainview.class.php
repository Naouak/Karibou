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

				// Empty array for prefs
				$prefs = array();

				// Get the default preferences
				$conf_mod_name = $miniapp->getConfigModel();
				if(!empty($conf_mod_name)) {
					$conf_mod = new $conf_mod_name($this->db, $this->currentUser, $miniappName, $app);
					$fields = $conf_mod->formFields();

					if(!empty($fields)) foreach($fields as $key => $field) {
						if(isset($field["value"])) {
							$prefs[$key] = $field["value"];
						}
					}
				}

				$lang = $GLOBALS["config"]["lang"];

				// If the user is logged in, get its preferences
				if ($this->currentUser->isLogged()) {
                    $userPrefs = $this->currentUser->getPref($this->args['miniapp']);
                    if (is_array($userPrefs)) {
                        $prefs = array_merge($prefs, $userPrefs);
                    }
					$lang = $this->currentUser->getPref("lang", $lang);
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
				$this->assign("appTitle", $miniapp->getName($lang));
			}
		}
	}
}
?>
