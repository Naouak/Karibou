<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
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
class Annuaire extends Model
{
	function build()
	{
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "home") );
		
		$groups = $this->userFactory->getGroups();
		$this->assign('grouptree', $groups->getTree() );
		
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