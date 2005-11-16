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
 * Fetch Groups info to display a tree
 *
 * @package applications
 */
class GroupsDisplay extends Model
{
	
	function build()
	{
		$groups = $this->userFactory->getGroups();
		$this->assign('grouptree', $groups->getTree() );

		if( $this->currentUser->isLogged() )
		{
			$this->assign('currentuser_login', $this->currentUser->getLogin() );
		}
	}

	function filterChilds($group)
	{
		if( ($this->parent->getLeft() < $group->getLeft()) && 
			($group->getRight() < $this->parent->getRight()) )
			return true;
		return false;
	}
}
?>