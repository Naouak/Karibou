<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

/**
 * Used to check Log-in of a user
 * 
 * @package applications
 */

class AuthMysql extends Auth
{
	protected $db;
	
	function __construct($db)
	{
		$this->db = $db;
	}
	
	/**
	 * @param String $user
	 * @param String $pass
	 * @return boolean
	 */
	function login($user, $pass)
	{
		$qry = "SELECT * FROM ".$GLOBALS['config']['bdd']["frameworkdb"].".users
			WHERE `login`='".$user."' AND `password`=PASSWORD('".$pass."')";
		try
		{
			$stmt = $this->db->query($qry);
		}
		catch( PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		if( $row = $stmt->fetch() )
		{
			$stmt->fetchAll();
			unset($stmt);
			return true;
		}
		$stmt->fetchAll();
		unset($stmt);
		return false;
	}

}

?>
