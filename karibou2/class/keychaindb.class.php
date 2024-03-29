<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

/**
 * @package framework
 */
class KeyChainDB extends KeyChain
{
	protected $db;

	protected $saved_data = array();

	function __construct(PDO $db, CurrentUser $user)
	{
		parent::__construct($user);
		$this->db = $db;

		if( isset($_SESSION['keychain_saved_data']) )
		{
			$this->saved_data = $_SESSION['keychain_saved_data'];
		}
	}
	
	function __destruct()
	{
		$_SESSION['keychain_saved_data'] = $this->saved_data;
		
		parent::__destruct();
	}
	
	function createStorage()
	{
		$qry = "DELETE FROM keychain WHERE user_id='".$this->user->getID()."' ; ";
		try
		{
			$stmt = $this->db->exec($qry);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
	}
	
	function getData($name)
	{
		if( ! isset($this->saved_data[$name]) )
		{
			try
			{
				$stmt = $this->db->prepare("SELECT data FROM keychain WHERE user_id=:user AND name=:name LIMIT 1");
				$stmt->bindValue(":user", $this->user->getID());
				$stmt->bindValue(":name", $name);
				$stmt->execute();
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
			if( $tab = $stmt->fetch(PDO::FETCH_ASSOC) )
			{
				$this->saved_data[$name] = $tab['data'];
				$stmt->fetchAll();
			}
			else
			{
				unset($stmt);
				return FALSE;
			}
			unset($stmt);
		}
		
		return $this->saved_data[$name];
	}
	
	function setData($name, $data)
	{
		/*$data WAS $encrypted*/
		try
		{
			$qry = $this->db->prepare("DELETE FROM keychain WHERE user_id=:user AND name=:name");
			$qry->bindValue(":user", $this->user->getID());
			$qry->bindValue(":name", $name);
			$qry->execute();
			$qry = $this->db->prepare("INSERT INTO keychain (user_id, name, data) VALUES (:user, :name, :data)");
			$qry->bindValue(":user", $this->user->getID());
			$qry->bindValue(":name", $name);
			$qry->bindValue(":data", $data);
			$qry->execute();
			$this->saved_data[$name] = $data;
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
	}
	
	function getAllData()
	{
		try
		{
			$stmt = $this->db->query("SELECT name, data FROM keychain WHERE user_id='".$this->user->getID()."'");
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		while( $tab = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$this->saved_data[$tab['name']] = $tab['data'];
		}
		unset($stmt);
		
		return $this->saved_data;
	}

	function getNames()
	{
		try
		{
			$stmt = $this->db->query("SELECT name FROM keychain WHERE user_id='".$this->user->getID()."' ");
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		$vars = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$ret = array();
		foreach($vars as $v)
		{
			$ret[] = $v['name'];
		}
		unset($stmt);
		return $ret;
	}
	
	function relock($passphrase)
	{
		parent::relock($passphrase);
		$this->saved_data = array();
		if( isset($_SESSION['keychain_saved_data']) )
			unset($_SESSION['keychain_saved_data']);
	}
	
}

?>
