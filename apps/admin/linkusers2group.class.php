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
		
			$qry_group_model = "INSERT INTO ".$annudb.".group_user (user_id, group_id) VALUES ";
			$qry_group = "";
			$first = true;
			foreach($usertogroup as $u)
			{
				if ($user = $this->userFactory->prepareUserFromLogin($u)) {
					$this->userFactory->setUserList();
					if($user->getID()==0)
					{
						if(isset($_POST['create_allow']))//non connu => creation authorisee?
						{
							$qry_NewUser = "INSERT INTO ".$annudb.".users (login) VALUES ('".$u."')";
							try
							{
								$this->db->exec($qry_NewUser);
							}
							catch( PDOException $e )
							{
								Debug::display($qry_NewUser);
								Debug::kill($e->getMessage());
							}
							$user = $this->userFactory->prepareUserFromLogin($u);
							$this->userFactory->setUserList();
						}					
					}
					if($user->getID()!=0)
					{
						$qry_group .= $qry_group_model ."('".$user->getId()."', '".$_GET['group']."') ; ";
					}
				} 
			}

		try
		{
			$this->db->exec($qry_group);
		}
		catch( PDOException $e )
		{
			Debug::display($qry_group);
			Debug::kill($e->getMessage());
		}


		}


		if( isset($_GET['group']) )
		{
			//affichage du groupe en selection
			$okgrouping = $_GET['group'];
			$this->assign('okgrouping', $okgrouping );

		}



		$groups = $this->userFactory->getGroups();
		$this->assign('grouptree', $groups->getTree() );
	}

}
?>