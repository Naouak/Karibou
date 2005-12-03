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
class AddressBook extends Model
{
	function build()
	{

		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "home") );
	
		$qry = "SELECT * FROM addressbook_user au, addressbook a WHERE 
			a.id=au.profile_id AND
			au.user_id='".$this->currentUser->getID()."' LIMIT 20";
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
	}

}

?>