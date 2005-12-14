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
	
	protected $id;
	
	protected $path;
	protected $patharray; //This array contains the names of each element
	protected $patharrayid; //This array contains the ids of each element (in the same order as $patharray)
	
	protected $sysinfos;
	protected $rights;
	protected $versions;

	function __construct ($db, $type = FALSE, $path = FALSE, $id = FALSE)
	{
		$this->db			= $db;

		if ($this->db !== FALSE)
		{
			$this->sysinfos	= array();
			$this->rights	= array();
			$this->versions	= array();
			
			//Using the path to retrieve the infos
			if ( ($type !== FALSE) && ($path !== FALSE) )
			{
				$this->path	= $path;
				$this->setPathArrayId();

				$this->retrieveAllInfos();
			}
			//Using the id to retrieve the infos
			elseif ($id !== FALSE)
			{
				$this->id = $id;
				$this->retrieveAllInfos();
				$this->setPathArray();
			}
			else
			{
				Debug::kill("No decent way...");
			}
		}
	}

	function retrieveAllInfos ()
	{
		$this->sysinfos = $this->retrieveSysInfos();
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
				WHERE	fileshare_sysinfos.id = '".$this->getElementId()."'
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
				WHERE	fileshare_rights.id = '".$this->getElementId()."'
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

	public function getLastVersionInfo($key)
	{
		$lastversion = end($this->versions);
		if (isset($lastversion[$key]))
		{
			return $lastversion[$key];
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getAllVersions()
	{
		var_dump($this->versions);
		return $this->versions;
	}

	//Method setting the $this->versions var
	function retrieveVersions ()
	{
		$sql = "
				SELECT *
				FROM fileshare_versions
				WHERE	fileshare_versions.id = '".$this->getElementId()."'
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


	//Return path from rootdir without trailing slash
	public function getPath()
	{
		if ( (strrpos($this->path,'/')== strlen($this->path)-1) )
		{
			$path = substr($this->path,0,strlen($this->path)-1);
		}
		else
		{
			$path = $this->path;
		}
		
		return $path;
	}
	
	public function getPathArray()
	{
		$path = $this->getPath();
		preg_match_all("/([^\/]+)/", $path, $out, PREG_PATTERN_ORDER);
		return $out[1];
	}
	

	//Method setting the pathArray var
	function setPathArrayId()
	{
		$this->patharray = $this->getPathArray();
		$this->patharrayid = array();
		
		foreach($this->patharray as $level => $name)
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
				$this->patharrayid[] = $thisid;
			}
			else
			{
				$this->patharrayid[] = FALSE;
			}
			unset($stmt);
		}
	}

	//Method getting the element id
	function getElementId()
	{
		if (!isset($this->id))
		{
			if (!isset($this->patharrayid))
			{
				$this->setPathArrayId();
			}
			//NULL case ?
			if (count ($this->patharrayid) > 0)
			{
				return end($this->patharrayid);
			}
			else
			{
				return NULL;
			}
		}
		else
		{
			return $this->id;
		}
	}
	
	//Method setting the pathArray var
	function setPathArray()
	{
		//$this->patharray = $this->getPathArray();
		//$this->patharrayid = array();
		
		$cid = $this->id;
		$rpatharray = array();
		$rpatharrayid = array();
		
		//need loop protection... if parent id is already in id list...
		
		while (isset($cid) && ($cid != NULL) )
		{
			$sql = "
					SELECT id, parent, name
					FROM fileshare_sysinfos
					WHERE `id` = '".$cid."'
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
			
			if (count($tab) > 0)
			{
				$cid = $tab[0]["parent"];
				
				$rpatharrayid[] =  $tab[0]["id"];
				$rpatharray[] = $tab[0]["name"];
			}
			else
			{
				$rpatharrayid[] = NULL;
				$rpatharray[] = NULL;
				$cid = NULL;
			}
			unset($stmt);
		}
		$this->patharray = array_reverse($rpatharray);
		$this->patharrayid = array_reverse($rpatharrayid);
		if (count($this->patharray) > 0)
		{
			$path = "";
			foreach ($this->patharray as $name)
			{
				$path .= "/".$name;
			}
			$this->path = $path;
		}
	}
	
}

?>