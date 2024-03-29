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
class UserAdmin extends Model
{
	function build()
	{
		$user = $this->userFactory->prepareUserFromLogin($this->args['username']);
		$this->assign('user', $user);

		$groups = $this->userFactory->getGroups();

		//repere tous les groupes ou se trouve notre user
		foreach($groups as $g) 
		{
			if($user->isInGroup($this->db,$g->getId())==TRUE)  {
					$g->checked = TRUE;
					//echo "<br>".$g->getId();
			}
		}

		/*
		$user->setGroups($this->db);
		$usergroups = $user->getGroups();
		foreach($usergroups as $ug)
		{
			foreach($groups as $g)
			{
				if( $ug->getId() == $g->getId() )
				{
					$g->checked = TRUE;
					break;
				}
			}
		}
		*/


		$this->assign('grouptree', $groups->getTree() );
	}

}
?>
