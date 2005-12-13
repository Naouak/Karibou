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
class KDBFSElement
{
	protected $db;
	
	protected $name;
	
	protected $sysinfos;
	protected $rights;
	protected $versions;

	function __construct ($db, $type, $name, $parent)
	{
		$this->db			= $db;

		if ($this->db !== FALSE)
		{
			$this->name		= $name;
			
			$this->sysinfos	= array();
			$this->rights	= array();
			$this->versions	= array();
			
			$this->retrieveAllInfos();
		}
	}

	function retrieveAllInfos ()
	{
		$this->sysinfos = $this->retrieveSysInfos();
		$this->id = $this->sysinfos['id'];
		
		$this->rights = $this->retrieveRights();
		$this->versions = $this->retrieveVersions();
		
	}

	public function getSysInfos($key)
	{
		if (isset($this->sysinfos[$key]))
		{
			return $this->sysinfos[$key];
		}
		else
		{
			return FALSE;
		}
	}

	//Method setting the $this->sysinfos var
	protected function retrieveSysInfos ()
	{
		$sql = "
				SELECT *
				FROM fileshare_sysinfos
				WHERE	fileshare_sysinfos.name = '".$this->name."'
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		$tab = $stmt->fetch(PDO::FETCH_ASSOC);
		
		unset($stmt);
		
		return $tab;
	}

	//Method setting the $this->rights var
	function retrieveRights ()
	{
		$sql = "
				SELECT *
				FROM fileshare_rights
				WHERE	fileshare_rights.id = '".$this->id."'
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		$tab = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		unset($stmt);
		
		return $tab;
	}

	public function getVersionInfo($key)
	{var_dump($this->versions);
		if (isset($this->versions[0][$key]))
		{
			return $this->versions[0][$key];
		}
		else
		{
			return FALSE;
		}
	}

	//Method setting the $this->versions var
	function retrieveVersions ()
	{
		echo $sql = "
				SELECT *
				FROM fileshare_versions
				WHERE	fileshare_versions.id = '".$this->id."'
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		$tab = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		unset($stmt);
		
		return $tab;
	}
	
	//Method getting the folderid
	function getFolderId($patharray)
	{
		foreach($patharray as $level => $name)
		{
			//SQL select null parent for root directory
			if ($level == 0)
			{
				$parenttxt = "IS NULL";
			}
			else
			{
				if (isset($thisid))
				{
					$parenttxt = "= '".$thisid."'";
					unset($thisid);
				}
				else
				{
					Debug::kill("FileShare : No parent id for child...");
				}
			}
			
			$sql = "
					SELECT *
					FROM fileshare_sysinfos
					WHERE `name` = '".$name."'
					AND `parent` $parenttxt
				";			
				
			try
			{
				$stmt = $this->db->query($sql);
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
			
			$tab = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			if (isset($tab[0]["id"]))
			{
				$thisid = $tab[0]["id"];
			}
			unset($stmt);
		}
		
		if (isset($thisid))
		{
			return $thisid;
		}
		else
		{
			return NULL;
		}
	}
	
}

?>