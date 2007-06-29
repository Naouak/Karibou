<?php
/**
 * @copyright 2006 Charles Anssens
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
class LinkUsers2Group extends Model
{
	function build()
	{
		
		if( isset($_POST['usertogroup']) && isset($_GET['group']) )
		{
			//explode liste users ;
			$usertogroup = explode(";",$_POST['usertogroup']);
			



			$annudb = $GLOBALS['config']['bdd']['frameworkdb'];
		
			$qry_group = "INSERT INTO ".$annudb.".group_user (user_id, group_id) VALUES ";
			$first = true;
			foreach($usertogroup as $u)
			{
				if ($user = $this->userFactory->prepareUserFromLogin($u)) {
					echo $u.$user->getID();
					if( !$first ) $qry_group .= ", ";
					$qry_group .= "('".$user->getId()."', '".$_GET['group']."')";
					$first = false;
				}
			}
			$qry_group .= " ; ";

		try
		{
			//$this->db->exec($qry_group);
			echo $qry_group;
		}
		catch( PDOException $e )
		{
			Debug::display($qry_group);
			Debug::kill($e->getMessage());
		}


		}

		if( isset($_GET['group']) )
		{
			//$this->assign('userlist', $this->userFactory->getUsersFromSearch($_GET['search']) );
			//affichage du groupe en selection
			$okgrouping = $_GET['group'];
			$this->assign('okgrouping', $okgrouping );

		}



		$groups = $this->userFactory->getGroups();
		$this->assign('grouptree', $groups->getTree() );
	}

}
?>