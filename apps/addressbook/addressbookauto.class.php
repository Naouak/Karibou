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
 * @package applications
 */
class AddressBookAuto extends Model
{
	function build()
	{
		if( isset($this->args['postname'], $_POST[$this->args['postname']]) )
		{
			$post = $_POST[$this->args['postname']];
			$qry = "
			SELECT a.* , ae.* FROM
				addressbook_user au, addressbook a, addressbook_email ae
			WHERE 
				a.id=au.profile_id AND
				a.id=ae.profile_id AND ae.type='INTERNET' AND
				(a.firstname LIKE '".$post."%' OR a.lastname LIKE '".$post."%' OR a.surname LIKE '".$post."%' OR ae.email LIKE '".$post."%') AND
				au.user_id='".$this->currentUser->getID()."'
		UNION
			SELECT a.* , ae.* FROM
				".$GLOBALS['config']['bdd']["annuairedb"].".profile a,
				".$GLOBALS['config']['bdd']["annuairedb"].".profile_email ae
			WHERE 
				a.id=ae.profile_id AND ae.type='INTERNET' AND
				(a.firstname LIKE '".$post."%' OR a.lastname LIKE '".$post."%' OR a.surname LIKE '".$post."%' OR ae.email LIKE '".$post."%')
		LIMIT 10";
		
			try
			{
				$stmt = $this->db->query($qry);
			}
			catch( PDOException $e )
			{
				Debug::kill($e->getMessage());
			}
			$this->assign('addresses', $stmt->fetchAll(PDO::FETCH_ASSOC));
			unset($stmt);
			Debug::$display = FALSE;
		}
	}

}

?>