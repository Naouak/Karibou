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
		$annudb = $GLOBALS['config']['bdd']['frameworkdb'];
		


		$set = "login='".$_POST['login']."', ";
		if( !empty($_POST['pass']) )
		{
			$set .= "pass=PASSWORD('".$_POST['pass']."'), ";
		}
		//$set .= "email='".$_POST['email']."' ";
		$qry_user = "UPDATE ".$annudb.".users SET ".$set." WHERE id='".$_POST['id']."' ; ";
		
		
		$qry_raz_user_group = "DELETE FROM ".$annudb.".group_user WHERE user_id='".$_POST['id']."' ; " ;


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
			$qry_group = "INSERT INTO ".$annudb.".group_user (user_id, group_id) VALUES ";
			$first = true;
			foreach($groups as $g)
			{
				if( !$first ) $qry_group .= ", ";
				$qry_group .= "('".$_POST['id']."', '".$g."')";
				$first = false;
			}
			$qry_group .= " ; ";
		}
		try
		{
			$this->db->exec($qry_user);
		}
		catch( PDOException $e )
		{
			Debug::display($qry_user);
			Debug::kill($e->getMessage());
		}
		try
		{
			$this->db->exec($qry_raz_user_group);
		}
		catch( PDOException $e )
		{
			Debug::display($qry_raz_user_group);
			Debug::kill($e->getMessage());
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

}
?>
