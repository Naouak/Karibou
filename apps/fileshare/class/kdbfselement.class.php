<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/

define ("READ", 4);
define ("UPDATE", 2);
define ("WRITE", 1);

/**
 *
 * @package applications
 **/
class KDBFSElement
{
	protected $db;
	protected $userFactory;
	
	protected $id;
	
	protected $path;
	protected $patharray; //This array contains the names of each element
	protected $patharrayid; //This array contains the ids of each element (in the same order as $patharray)
	
	protected $sysinfos;
	protected $rights;
	protected $versions;
	
	public $creator;
	public $userrights;

	function __construct (PDO $db, UserFactory $userFactory, $type = FALSE, $path = FALSE, $id = FALSE)
	{
		$this->db			= $db;
		$this->userFactory	= $userFactory;

		if ($this->db !== FALSE)
		{
			$this->sysinfos	= array();
			$this->rights	= array();
			$this->versions	= array();
			
			//Using the path to retrieve the infos
			if ( ($type !== FALSE) && ($path !== FALSE) )
			{
				$this->path	= $path;
				//$this->setPathArrayId();
				$this->id = $this->getElementId();
				if (isset($this->id) && !is_null($this->id))
				{
					$this->retrieveAllInfos();
				}
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
		
		$this->getUserRights();
	}

	function retrieveAllInfos ()
	{
		$this->sysinfos = $this->retrieveSysInfos();
		$this->rights = $this->retrieveRights();
		$this->versions = $this->retrieveVersionsObjects();
		
		if ($this->getSysInfos("creator") !== FALSE)
		{
			$this->creator = $this->userFactory->prepareUserFromId($this->getSysInfos("creator"));
		}
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
				WHERE	id = ".$this->getElementId()."
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$tab = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			return $tab[0];
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
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
		if (count($this->versions)>0)
		{
			$lastversion = end($this->versions);
			
			if ($lastversion->getInfo($key) !== FALSE)
			{
				return $lastversion->getInfo($key);
			}
		}
		return FALSE;
	}
	
	public function getAllVersions()
	{
		return array_reverse($this->versions);
	}

	//Method setting the $this->versions var
	protected function retrieveVersions ()
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

	//Method setting the version object
	function retrieveVersionsObjects ()
	{
		$versions = $this->retrieveVersions();
		$versionsobjects = array();
		foreach ($versions as $version)
		{
			$version["user"] = $this->userFactory->prepareUserFromId($version["user"]);
			$versionsobjects[] = new KDBFSElementVersion ($version);
		}
		return$versionsobjects;
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
/*	
	public function getParentPath()
	{
		$patharray = $this->getPathArray();
		if (count($patharray)>0)
		{
			array_pop($patharray);
			$parentpath = "";
			foreach ($patharray as $name)
			{
				$path .= "/".$name;
			}
			return $path;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getParentPath64()
	{
		$path = $this->getParentPath();
		if (strlen($path) > 0)
		{
			return base64_encode($path);
		}
		else
		{
			return FALSE;
		}
	}
*/

	//Return parent path of actual dir
	public function getParentPath()
	{

		if ($this->getPath() != $this->getName())
		{
			$parentpath = substr($this->getPath(), 0, strlen($this->getPath()) - strlen($this->getName())-1);
		}
		else
		{
			$parentpath = '';
		}
		
		return $parentpath;
	}
	
	//Return Base64 parent path
	public function getParentPathBase64 ()
	{
		return base64_encode($this->getParentPath());
	}
	
	

	//Method setting the pathArrayId var from the patharray
	function setPathArrayId()
	{
		$this->patharray = $this->getPathArray();
		$this->patharrayid = array();
		
		if (count($this->patharray) > 0)
		{
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
						AND `deleted` = 0
					";			
					
				try
				{
					$stmt = $this->db->query($sql);
				}
				catch(PDOException $e)
				{
					Debug::kill($e->getMessage());
				}
				
				if (isset($stmt))
				{
					$tab = $stmt->fetchAll(PDO::FETCH_ASSOC);
				}
				
				if (isset($tab, $tab[0]["id"]))
				{
				
					$thisid = $tab[0]["id"];
					$this->patharrayid[] = $thisid;
				}
				else
				{
					//$this->patharrayid[] = FALSE;
					unset($this->patharrayid);
					return FALSE;
				}
				unset($stmt);
			}
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	//Method getting the element id
	function getElementId()
	{
		if (!isset($this->id))
		{
			if (!isset($this->patharrayid))
			{
				$pathExists = $this->setPathArrayId();
			}
			else
			{
				$pathExists = TRUE;
			}
			
			//NULL case ?
			if ($pathExists /*count ($this->patharrayid) > 0*/)
			{
				$lastid = end($this->patharrayid);
				if ($lastid !== FALSE && $lastid !== NULL)
				{
					return $lastid;
				}
			}
		}
		else
		{
			return $this->id;
		}
		return NULL;
	}
	
	//Method returning TRUE if the element has been found in db, false if not
	function existsInDB ()
	{
		if (!isset($this->id) || is_null($this->id))
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
		
	}
	
	//Method setting the pathArray var from the ID
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
	
	/*
	Rights...
	  DEC BIN
	 - 4  100 : read
	 - 2  010 : update (add new version...)
	 - 1  001 : write (delete, rename...)
	Which give :
	 - 7 is administrator
	 - 6 is contributor
	 - 4 is reader
	*/
	public function getUserRights()
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$groupownerid = $this->getSysInfos("groupowner");
		
		if (
				$currentUser->getId() == $this->getSysInfos("creator") 
			||	$currentUser->getId() == $this->getLastVersionInfo("uploader")
			|| 	(isset($groupownerid) && $groupownerid != NULL && $currentUser->isInGroup($this->db, $groupownerid)) )
		{
			$this->rights = READ|UPDATE|WRITE; //Full rights
		}
		else
		{
			$this->rights = READ|UPDATE; //Read & update rights
		}
	}
	
	public function canRead()
	{
		if ($this->rights & READ == READ)
			return TRUE;
		else
			return FALSE;
	}
	
	public function canUpdate()
	{
		if ($this->rights & UPDATE == UPDATE)
			return TRUE;
		else
			return FALSE;
	}
	
	public function canWrite()
	{
		if ($this->rights & WRITE == WRITE)
			return TRUE;
		else
			return FALSE;
	}
	
	

}

?>