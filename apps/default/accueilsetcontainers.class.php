<?php

/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
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

class AccueilSetContainers extends FormModel
{
	function build()
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$containers = $currentUser->getPref('containers');
		$containers->setSizes($_GET['size']);
		$currentUser->setPref('containers', $containers );
	}
}

?>
