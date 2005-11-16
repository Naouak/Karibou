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
 * default admin page
 * 
 * @package applications
 */
class GroupsAdmin extends Model
{
	function build()
	{
		$groups = $this->userFactory->getGroups();
		$this->assign('grouptree', $groups->getTree() );
	}

}
?>