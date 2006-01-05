<?php 

/**
 * @version $Id: minichatgrand.class.php,v 1.2 2004/12/03 00:52:56 jon Exp $
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MiniChatConfig extends Model
{
	function build()
	{
	
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
	
		if( isset($this->args['maxlines']) )
		{
			$this->assign('maxlines', $this->args['maxlines']);
		}
		else
		{
			$this->assign('maxlines', $config["max"]["small"]);
		}
	}
}

?>