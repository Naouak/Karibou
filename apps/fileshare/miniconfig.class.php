<?php

/**
 * @copyright 2008 Gilles Dehaudt <tonton1728@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class FileShareMiniConfig extends Model
{
	function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		
		if( isset($this->args['maxfilesadd']) )
		{
			$this->assign('maxfilesadd', $this->args['maxfilesadd']);
		}
		
		if( isset($this->args['maxfilesdown']) )
		{
			$this->assign('maxfilesdown', $this->args['maxfilesdown']);
		}
		
	}
}
?>