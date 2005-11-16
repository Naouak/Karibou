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

class MiniAppEditConfig extends Model
{
	function build()
	{
		if( isset( $this->args['miniapp'] ) )
		{
			$currentUser = $this->userFactory->getCurrentUser();
			$miniapps = $currentUser->getPref('miniapps') ;
			$config = $miniapps[$this->args['miniapp']];
			
			$a = $this->appList->getApp($config['app']);
			$a->addView($config['configview'], $this->args['miniapp'], $config['args']);
			$this->assign("id", $this->args['miniapp']);
		}
		Debug::$display = false;
	}
}

?>
