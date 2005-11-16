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

class MiniAppAddAjax extends Model
{
	function build()
	{
		if( isset($this->args['miniapp']) )
		{
			$currentUser = $this->userFactory->getCurrentUser();
			$containers = $currentUser->getPref('containers') ;
			$miniapps = $currentUser->getPref('miniapps') ;
			$newapp = $miniapps->getNewApp($this->args['miniapp']);
			$containers->addApp($newapp);

			$config = $miniapps[$newapp];
			
			$a = $this->appList->getApp($config['app']);
			$a->addView($config['view'], $newapp, $config['args']);
			$this->assign("id", $newapp);
			$this->assign('size', $config['size']);
			
			if( isset($config['configview']) )
			{
				$this->assign('config', true);
			}

			$currentUser->setPref('containers', $containers );
			$currentUser->setPref('miniapps', $miniapps );
			Debug::$display = false;
		}
	}
}

?>