<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2003 Pierre Laden <pladen@elv.enic.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

require_once dirname(__FILE__).'/user.class.php';

/**
 * L'utilisateur courant
 *
 * @package framework
 */
class CurrentUser extends User
{

	/**
	 * @var Array
	 */
	public $prefs = Array();
	
	/**
	 * @var String
	 */
	protected $passphrase = FALSE;
	
	/**
	 * @var bool
	 */
	protected $logged = FALSE;

	/**
	 * @param PDO $db
	 * @param String $login
	 */
	function __construct()
	{
	}


	public function login()
	{
		$this->logged = TRUE;
	}
	
	public function isLogged()
	{
		return $this->logged;
	}
	
	/**
	 * @param PDO $db
	 * @param String $login
	 */
	public function update(PDO $db, $login, $create = false)
	{
		if( $this->setProperties($db, $login, $create) )
		{
			$this->getGroups();
			$this->setPrefs($db);
			return true;
		}
		return false;
	}
	
	function setPassPhrase($pass)
	{
		$this->passphrase = $pass;
	}
	
	function getPassPhrase()
	{
		return $this->passphrase;
	}
	
	/**
	 * @param PDO $db
	 * @param String $login
	 */
	protected function setProperties(PDO $db, $login, $create = false)
	{
		// Propriétés standards de l utilisateur

		$qry = "SELECT u.id,
				u.login,
				p.lastname,
				p.firstname,
				p.surname
		FROM
			".$GLOBALS['config']['bdd']['frameworkdb'].".users u LEFT OUTER JOIN
			".$GLOBALS['config']['bdd']['frameworkdb'].".profile p ON u.profile_id=p.id
		WHERE
			u.login='" . $login . "';";

		try
		{
			$stmt = $db->query($qry);
		}
		catch(PDOException $e)
		{
			Debug::kill($qry." : ".$e->getMessage());
		}
		if($tab = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->setFromTab($tab);
			unset($stmt);
			return TRUE;
		}
		else if( $create )
		{
			unset($stmt);
			$qry = "INSERT INTO ".$GLOBALS['config']['bdd']['frameworkdb'].".users (login) VALUES ('".$login."')";
			try
			{
				$db->exec($qry);
			}
			catch(PDOException $e)
			{
				Debug::kill($qry." : ".$e->getMessage());
			}
			$this->setFromTab( array('id' => $db->lastInsertId() , 'login' => $login ) );
			return TRUE;
		}
		unset($stmt);
		return FALSE;
	}

	
	/**
	 * @param PDO $db
	 */
	protected function setPrefs(PDO $db)
	{
		$qry = "SELECT * FROM prefs WHERE user_id=:user";
		try
		{
			$stmt = $db->prepare($qry);
			$stmt->bindValue(":user", $this->id);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			Debug::kill($qry." : ".$e->getMessage());
		}
		while($tab = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->prefs[$tab['name']] = $tab["value"];
		}
	}

	public function savePrefs(PDO $db)
	{
		try
		{
			$qry_delete = $db->prepare("DELETE FROM prefs WHERE user_id=:id");
			$qry_insert = $db->prepare("INSERT INTO prefs(user_id, name, value) VALUES (:id, :name, :value)");
			$qry_delete->bindValue(":id", $this->id);
			$qry_delete->execute();
			$qry_insert->bindValue(":id", $this->id);
			foreach ($this->prefs as $name => $value)
			{
				$qry_insert->bindValue(":name", $name);
				$qry_insert->bindValue(":value", $value);
				$qry_insert->execute();
			}
		}
		catch(PDOException $e)
		{
			Debug::kill($qry." / ".$e->getMessage());
		}
	}
	
	public function getPref($name, $default = false)
	{
		if( isset($this->prefs[$name]) )
		{
			return unserialize($this->prefs[$name]);
		}
		return $default;
	}
	public function setPref($name, $value)
	{
		$this->prefs[$name] = serialize($value);
	}
	public function deletePref($name)
	{
		if (isset($this->prefs[$name]))
		{
			unset($this->prefs[$name]);
		}
	}
}

?>
