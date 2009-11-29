<?php

class DefaultModel extends Model {
	public function build() {
		$this->assign("lang", $this->smarty->getLanguage());
		$appNames = MiniAppFactory::listApplications();
		$appArray = Array();
		foreach($appNames as $appName) {
			$appArray[$appName] = MiniAppFactory::buildApplication($appName);
		}
		ksort($appArray);
		$this->assign("apps", $appArray);
		$this->assign("karibou_base_url", $GLOBALS['config']['base_url']);
		if ($this->currentUser->isLogged())
		{
			$keychain = KeyChainFactory::getKeyChain($this->currentUser);
			if (!$keychain->unLock())
			{
				if (isset($_SESSION["temp_session_password"]) && (strlen($_SESSION["temp_session_password"]) > 0))
				{
					// Ok, warn the user that his keychain is dead right now, we needs his new password...
					// It means we won't show him the applications and so on, we will only display input field or something like that
					// So that he can put in his/her old password
					$this->assign("keychainError", true);
					$keychainError = true;
					if ($_SESSION["bad_keychain_attempt"])
						$this->assign("secondAttempt", true);
				}
			}
		}

		// RSA Key
		$krypt = new Krypt();
		$pubkey = $krypt->getPublicKey();
		$this->assign("pubkey_exp", binToHex($pubkey->getExponent()) );
		$this->assign("pubkey_mod", binToHex($pubkey->getModulus()) );
	}
}

?>
