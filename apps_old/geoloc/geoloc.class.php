<?php 

/**
 * @version $Id $
 * @copyright 2007 Antoine Reversat
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class Geoloc extends Model
{
	public function build()
	{
	
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
	
		if ($this->currentUser->isLogged())
		{
			$gkey = $GLOBALS['config']['geoloc']['gkey'];

			if(array_key_exists('login', $this->args)){
				$this->assign('search', "var user = \"".$this->args['login']."\";");
			}
			$this->assign('gkey', $gkey);

		}
	
	}
}

?>
