<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * Display all members of a group
 * 
 * @package applications
 */
class GroupDisplay extends Model
{
	function build()
	{
	
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "groupdisplay") );
	
		if (isset($this->args['name']))
		{
			$select = "g.name='".$this->args['name']."'";
		}
		elseif (isset($this->args['id']))
		{
			$select = "g.id='".$this->args['id']."'";
		}
		else
		{
			Debug::kill("No argument");
		}

		//Select infos on the group to display
		$qry = "SELECT
				g.*
			FROM 
				".$GLOBALS['config']['bdd']["frameworkdb"].".groups g
			WHERE 
				".$select;
		try
		{
			$stmt = $this->db->prepare($qry);
			$stmt->execute();
		}
		catch ( PDOException $e )
		{
			Debug::kill($e->getMessage() );
		}
		
		$thegroup = $stmt->fetchAll(PDO::FETCH_ASSOC) ;

		//Select all users that belong to "thegroup" (N levels)
		$qry = "SELECT
				u.login,
				u.id as id,
				g.name as groupname,
				g.id as groupid,
				p.id as profileid,
				p.firstname,
				p.lastname,
				gu.role
			FROM 
				".$GLOBALS['config']['bdd']["frameworkdb"].".groups g ,
				".$GLOBALS['config']['bdd']["frameworkdb"].".group_user gu ,
				".$GLOBALS['config']['bdd']["frameworkdb"].".users u LEFT OUTER JOIN
				".$GLOBALS['config']['bdd']["frameworkdb"].".profile p
			ON u.profile_id=p.id
			WHERE
				g.id=gu.group_id AND
				gu.user_id=u.id AND
				gu.visibility='visible' AND
				g.left >= ".$thegroup[0]["left"]." AND
				g.right <= ".$thegroup[0]["right"]."
			ORDER BY g.name, p.lastname, p.firstname, u.login";
		try
		{
			$stmt = $this->db->prepare($qry);
			$stmt->execute();
		}
		catch ( PDOException $e )
		{
			Debug::kill($e->getMessage() );
		}
		
		
		$this->assign('thegroup', $thegroup[0]);
		
		if( $this->currentUser->isLogged() )
		{
			$this->assign('currentuser_login', $this->currentUser->getLogin() );
		}
		
		$userlist = $stmt->fetchAll(PDO::FETCH_ASSOC) ;
		foreach($userlist as &$u)
		{
			if( is_file(KARIBOU_PUB_DIR.'/profile_pictures/'.$u['profileid'].".jpg") )
			{
				$u["picture"] = "/pub/profile_pictures/".$u['profileid'].".jpg";
			}
			else
			{
				$u["picture"] = "/themes/karibou2/images/0.jpg";
			}
			if (($this->currentUser->getId() == $u['id']) && ($u['role'] == "admin"))
			{
				$this->assign("group_admin", true);
			}
		}
		$this->assign('userlist', $userlist);
	}

}
?>
