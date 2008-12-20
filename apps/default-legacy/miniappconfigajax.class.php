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

class MiniAppConfigAjax extends Model
{
	function build()
	{
		if( isset($_POST['miniappid']) )
		{
			$currentUser = $this->userFactory->getCurrentUser();
			$miniapps = $currentUser->getPref('miniapps') ;
			
			$args = $_POST;
			
			$miniapps->setArgs( $_POST['miniappid'], $args );

			$config = $miniapps[$_POST['miniappid']];
			
			$a = $this->appList->getApp($config['app']);
			$a->addView($config['view'], $_POST['miniappid'], $config['args']);
			$this->assign("id", $_POST['miniappid']);
			$this->assign('size', $config['size']);
			
			if( isset($config['configview']) )
			{
				$this->assign('config', true);
			}


			$currentUser->setPref('miniapps', $miniapps );
			Debug::$display = false;
		}
	}
}

?>