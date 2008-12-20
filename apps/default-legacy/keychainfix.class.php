<?php

/**
 * @copyright 2008 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/


/**
 * 
 * @package applications
 */

class KeyChainFix extends FormModel
{
	function build()
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$newPassword = $_SESSION["temp_session_password"];
		$oldPassword = filter_input(INPUT_POST, "old_password");
		if ((strlen($newPassword) > 0) && (strlen($oldPassword) > 0))
		{
			// This is where the fun begins...
			$currentUser->setPassPhrase(sha1($oldPassword));
			$keychain = KeyChainFactory::getKeyChain($currentUser);
			if ($keychain->unlock(sha1($oldPassword)))
			{
				// The keychain can be unlocked, so it means that the old password is good. Excellent.
				$keychain->relock(sha1($newPassword));
				unset($_SESSION["temp_session_password"]);
			}
			else
			{
				// The old password given isn't the right one... bad user, bad !
				$_SESSION["bad_keychain_attempt"] = true;
			}
			$currentUser->setPassPhrase(sha1($newPassword));
		}
		else
		{
			$_SESSION["bad_keychain_attempt"] = true;
		}
	}
}

?>