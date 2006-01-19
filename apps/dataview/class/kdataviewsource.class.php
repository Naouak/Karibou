<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/

/**
 *
 * @package applications
 **/
class KDataViewSource
{
	protected $db;
	protected $userFactory;
	protected $data;
	protected $records;

	function __construct(PDO $db, UserFactory $userFactory, $tablename, Array $data)
	{
		$this->db 			= $db;
		$this->userFactory	= $userFactory;
		
		$this->tablename	= $tablename;
		$this->data			= $data;
	}
	
	/* Get System Infos */
	function getTableName ()
	{
		return $this->tablename;
	}
	
	function isPublic ($key)
	{
		return in_array($key, $this->data["public"]);
	}
	
	function isPrivate ($key)
	{
		return in_array($key, $this->data["private"]);
	}
	
	function getFields ()
	{
		return array_merge($this->data["public"], $this->data["private"]);
	}
	
	/* Get Records */
	function getRecords($limit = FALSE, $page = 1)
	{
		$page--;
		if ($limit !== FALSE)
		{
			$limit = "LIMIT ".$page*$limit.",".$limit;
		}
		else
		{
			$limit = "";
		}
	
		$sql = "
				SELECT *
				FROM ".$this->getTableName()."
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$tab = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			return $tab;
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
	}
	
	function getRecordsFromSearch ($field, $search)
	{
	}
}

?>