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
				".$GLOBALS['config']['bdd']["annuairedb"].".groups g
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
				g.name as groupname,
				g.id as groupid,
				p.*
			FROM 
				".$GLOBALS['config']['bdd']["annuairedb"].".groups g ,
				".$GLOBALS['config']['bdd']["annuairedb"].".group_user gu ,
				".$GLOBALS['config']['bdd']["annuairedb"].".users u LEFT OUTER JOIN
				".$GLOBALS['config']['bdd']["annuairedb"].".profile p
			ON u.profile_id=p.id
			WHERE
				g.id=gu.group_id AND
				gu.user_id=u.id AND
				gu.visibility='visible' AND
				g.left >= ".$thegroup[0]["left"]." AND
				g.right <= ".$thegroup[0]["right"]."
			ORDER BY g.name, p.lastname, p.firstname";
		try
		{
			$stmt = $this->db->prepare($qry);
			$stmt->execute();
		}
		catch ( PDOException $e )
		{
			Debug::kill($e->getMessage() );
		}
		$userlist = $stmt->fetchAll(PDO::FETCH_ASSOC) ;
		foreach($userlist as &$u)
		{
			if( is_file(KARIBOU_PUB_DIR.'/profile_pictures/'.$u['id'].".jpg") )
			{
				$u["picture"] = "/pub/profile_pictures/".$u['id'].".jpg";
			}
			else
			{
				$u["picture"] = "/themes/default/images/0.jpg";
			}
		}
		$this->assign('userlist', $userlist);
		
		$this->assign('thegroup', $thegroup[0]);
		
		if( $this->currentUser->isLogged() )
		{
			$this->assign('currentuser_login', $this->currentUser->getLogin() );
		}
	}

}
?>