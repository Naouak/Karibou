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

class MiniAppView extends Model
{
	function build()
	{
		if( isset($this->args['id'], $this->args['app'], $this->args['view'], $this->args['args']) )
		{
			$a = $this->appList->getApp($this->args['app']);
			$a->addView($this->args['view'], $this->args['id'], $this->args['args']);
			$this->assign("id", $this->args['id']);
			$this->assign('size', $this->args['size']);
			if( isset($this->args['configview']) )
			{
				$this->assign('config', true);
			}
		}
	}
}

?>
