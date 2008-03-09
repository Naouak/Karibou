<?php
/**
 * @copyright 2003 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * @package applications
 */
class HeaderModel extends Model
{
	function build()
	{
		$this->assign("basehref", $GLOBALS["config"]['site']['base_url']);
		if( $theme = $this->currentUser->getPref('theme') )
		{
			$this->assign("cssFile", $GLOBALS['config']['style']
				[$theme]
				['fichier_css']);
		}
		else
		{
			$this-> assign("cssFile", $GLOBALS['config']['style']
				[0]
				['fichier_css']);
		}
		
		$this->assign("styles", $GLOBALS['config']['style']);

		$this-> assign("currentUserName", 
			$this->currentUser->afficheUser() );
		
		$visiteurs = new Visiteurs($this->db);
		$visiteurs->countConnected();
		
		$this->assign("linkConnectedUsers",
			$visiteurs->getLinkConnectedUsers() );
			
		
		$minichat = $this->appList->getApp("login");
		$minichat->addView("loginform", "loginform");

		$minichat = $this->appList->getApp("minichat");
		$minichat->addView("mini", "mini_apps");
		
		
	}
	

}

?>
