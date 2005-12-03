<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * default page
 * 
 * @package applications
 */
class AnnuaireSearch extends Model
{
	function build()
	{
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "search") );
		
		if( $this->currentUser->isLogged() )
		{
			$this->assign('currentuser_login', $this->currentUser->getLogin() );
		}
		if( isset($_GET['search']) )
		{
			$this->assign('search', $_GET['search']);
			$this->assign('userlist', $this->userFactory->getUsersFromSearch($_GET['search']) );
		}
	}

}
?>
