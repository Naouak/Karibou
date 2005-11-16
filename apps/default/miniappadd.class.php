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

class MiniAppAdd extends FormModel
{
	function build()
	{
		if( isset($_GET['app']) )
		{
			$currentUser = $this->userFactory->getCurrentUser();
			$containers = $currentUser->getPref('containers') ;
			$miniapps = $currentUser->getPref('miniapps') ;
			$newapp = $miniapps->getNewApp($_GET['app']);
			$containers->addApp($newapp);
			$currentUser->setPref('containers', $containers );
			$currentUser->setPref('miniapps', $miniapps );
		}
	}
}

?>