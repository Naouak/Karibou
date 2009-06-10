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

class KeyChainReset extends FormModel
{
	function build()
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$newPassword = $_SESSION["temp_session_password"];
		if (strlen($newPassword) > 0)
		{
			// This is where the fun begins...
			$currentUser->setPassPhrase(sha1($newPassword));
			$keychain = KeyChainFactory::getKeyChain($currentUser);
			$keychain->create(sha1($newPassword));
		}
	}
}

?>
