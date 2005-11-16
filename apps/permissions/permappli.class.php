<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * @package applications
 */

class PermAppli extends Model
{
	function build()
	{
		$appli = $this->args['appli'];
		
		$this->assign('appli', $appli);
		
		$users = array();
		$userFactory = $this->userFactory;
		
		$groupList = $userFactory->getGroups();
		foreach( $groupList as $group )
		{
			$group->perm = "_DEFAULT_";
		}
		
		$qry = "SELECT group_id, permission
			FROM permissions_group WHERE appli='".$appli."'";
		$stmt = $this->db->prepare($qry);
		$stmt->execute();
		
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$groupList[$row['group_id']]->perm = $row['permission'];
		}
		$groups = array();
		foreach($groupList as $group)
		{
			$groups[] = $group;
		}
		$this->assign('groups', $groups);
		$this->assign('users', $users);
		
		$this->assign('droits', KPermissions::$id2txt);
		
		$this->assign('searchUser', false);
		if( isset($_GET['search']) )
		{
			$userlist = $userFactory->getUsersFromSearch($_GET['search']);
			$this->assign('userlist', $userlist);
			$this->assign('searchUser', true);
		}
	}
}

?>