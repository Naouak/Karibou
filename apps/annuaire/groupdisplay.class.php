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
 * Display all members of a group
 * 
 * @package applications
 */
class GroupDisplay extends Model
{
	function build()
	{
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

		$qry = "SELECT
				u.login,
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
				".$select."
			ORDER BY p.lastname, p.firstname";
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
		
		if( $this->currentUser->isLogged() )
		{
			$this->assign('currentuser_login', $this->currentUser->getLogin() );
		}
	}

}
?>