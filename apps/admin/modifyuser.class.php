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
class ModifyUser extends FormModel
{
	function build()
	{
		$annudb = $GLOBALS['config']['bdd']['annuairedb'];
		
		$set = "login='".$_POST['login']."', ";
		if( !empty($_POST['pass']) )
		{
			$set .= "pass=PASSWORD('".$_POST['pass']."'), ";
		}
		$set .= "email='".$_POST['email']."' ";
		$qry = "UPDATE ".$annudb.".users SET ".$set." WHERE id='".$_POST['id']."' ; ";
		
		$qry .= "DELETE FROM ".$annudb.".group_user WHERE user_id='".$_POST['id']."' ; ";
		$groups = array();
		foreach($_POST as $post => $value)
		{
			if( preg_match("/group([0-9]+)/", $post, $match) )
			{
				if( $match[1] == $value) $groups[] = $match[1];
			}
		}
		if( count($groups) > 0 )
		{
			$qry .= "INSERT INTO ".$annudb.".group_user (user_id, group_id) VALUES ";
			$first = true;
			foreach($groups as $g)
			{
				if( !$first ) $qry .= ", ";
				$qry .= "('".$_POST['id']."', '".$g."')";
				$first = false;
			}
			$qry .= " ; ";
		}
		try
		{
			$this->db->exec($qry);
		}
		catch( PDOException $e )
		{
			Debug::display($qry);
			Debug::kill($e->getMessage());
		}
	}

}
?>