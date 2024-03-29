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
	protected $permission;
	
	protected $id;
	
	protected $path;
	protected $patharray; //This array contains the names of each element
	protected $patharrayid; //This array contains the ids of each element (in the same order as $patharray)
	
	protected $sysinfos;
	protected $rights;
	protected $versions;
	
	protected $stats;
	
	public $creator;
	public $userrights;

	function __construct (PDO $db, UserFactory $userFactory, $permission, $type = FALSE, $path = FALSE, $id = FALSE, $data = FALSE, $rootdir = FALSE)
	{
		$this->db			= $db;
		$this->userFactory	= $userFactory;
		$this->permission = $permission;

		if ($this->db !== FALSE)
		{
			$this->sysinfos	= array();
			$this->rights	= array();
			$this->versions	= array();
			$this->stats	= array();
			
			//Using the path to retrieve the infos
			if ( /*($type !== FALSE) &&*/ ($path !== FALSE) )
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
			elseif ($id !== FALSE && $data === FALSE)
			{
				$this->id = $id;
				$this->retrieveAllInfos();
				$this->setPathArray();
			}
			elseif ($id !== FALSE && $data !== FALSE)
			{
				$this->setAllInfos($data);
				//Need to be loaded afterwards...
			}
			else
			{
				Debug::kill("No decent way...");
			}
		}
		
		if ($rootdir == FALSE)
		{
			$this->rootdir = KARIBOU_PUB_DIR.'/fileshare/share/';
		}
		else
		{
			$this->rootdir = $rootdir;
		}
		$this->fullpath = $this->rootdir.$this->path;
		
		$this->getUserRights();
	}

	function setAllInfos ($data)
	{
		$this->id 						= $data["id"];
		$this->sysinfos["id"] 			= $data["id"];
		$this->sysinfos["parent"] 		= $data["parent"];
		$this->sysinfos["name"] 		= $data["name"];
		$this->sysinfos["creator"] 		= $data["creator"];
		if ($this->getSysInfos("creator") !== FALSE)
		{
			$this->creator = $this->userFactory->prepareUserFromId($this->getSysInfos("creator"));
		}
		$this->setPathArray();

		$version["id"] 					= $data["id"];
		$version["versionid"] 			= $data["versionid"];
		$version["description"] 		= $data["description"];
		$version["user"] 				= $data["user"];
		$version["datetime"] 			= $data["datetime"];
		$version["timestamp"] 			= $data["timestamp"];
		$version["user"] 				= $this->userFactory->prepareUserFromId($version["user"]);
		$this->versions[0] 				= new KDBFSElementVersion ($version);

		$this->stats[0] = array();
		$this->stats[0]["elementid"]	= $data["id"];
		$this->stats[0]["versionid"]	= $data["versionid"];
		$this->stats[0]["hits"]			= $data["hits"];
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
		
		$this->stats = $this->retrieveStats();
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
SELECT fileshare_sysinfos.*, ".$GLOBALS['config']['bdd']['frameworkdb'].".groups.name as groupowner_name FROM fileshare_sysinfos
LEFT JOIN ".$GLOBALS['config']['bdd']['frameworkdb'].".groups ON ".$GLOBALS['config']['bdd']['frameworkdb'].".groups.id = fileshare_sysinfos.groupowner
WHERE fileshare_sysinfos.id = ".$this->getElementId()."
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
				SELECT *, TIMESTAMP(datetime) AS timestamp
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
	
	public function getFullPath()
	{
		if ($this->isFile())
		{
			return $this->fullpath;
		}
		elseif ($this->isDirectory())
		{
			preg_match("/(.*)[\/]{0,1}$/", $this->fullpath, $matches);
			return $matches[0];
		}
	}

	//Returns full file name (with extension)
	function getName()
	{
		preg_match("/([^\/])+$/", $this->fullpath, $matches);
		
		if (isset ($matches[0]))
		{
			return $matches[0];
		}
		else
		{
			return '';
		}
		
	}

	function getPathBase64()
	{
		return base64_encode($this->getPath());
	}

	public function getPathArray()
	{
		$path = $this->getPath();
		preg_match_all("/([^\/]+)/", $path, $out, PREG_PATTERN_ORDER);
		return $out[1];
	}

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
					(
						(
						$currentUser->getId() == $this->getSysInfos("creator") 
					||	$currentUser->getId() == $this->getLastVersionInfo("uploader")
					|| 	(isset($groupownerid) && $groupownerid != NULL && $currentUser->isInGroup($this->db, $groupownerid))
						) 
					&&
					$this->permission > _READ_ONLY_
					)
				||	$this->permission >= _FULL_WRITE_
			)
		{
			$this->rights = READ|UPDATE|WRITE; //Full rights
		}
		else
		{
			$this->rights = READ; //Read & update rights
		}
	}
	
	public function canRead()
	{
		if ( (($this->rights & READ) == READ) )
			return TRUE;
		else
			return FALSE;
	}
	
	public function canUpdate()
	{
		if ( (($this->rights & UPDATE) == UPDATE) )
			return TRUE;
		else
			return FALSE;
	}
	
	public function canWrite()
	{
		if ( (($this->rights & WRITE) == WRITE) )
			return TRUE;
		else
			return FALSE;
	}
	
	/* Download stat function */
	public function downloaded($versionid = FALSE)
	{
		if (!isset($versionid) || $versionid === FALSE)
		{
			$versionid = $this->getLastVersionInfo("versionid");
		}
	
		$sql = "
				INSERT INTO fileshare_stats
					(`elementid`, `versionid`, `userid`, `datetime`)
				VALUES
					('".$this->getElementId()."', '".$versionid."', '".$this->userFactory->getCurrentUser()->getId()."', NOW())
			";			
		try
		{
			$stmt = $this->db->exec($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		unset($stmt);
	}
	
	public function getHitsByVersion($versionid = FALSE)
	{
		if (!isset($versionid) || $versionid === FALSE)
		{
			$versionid = $this->getLastVersionInfo("versionid");
		}
		
		foreach($this->stats as $stat)
		{
			if ($stat["versionid"] == $versionid)
			{
				return $stat["hits"];
			}
		}
		return NULL;
	}
	
	public function retrieveStats ($versionid = FALSE)
	{
		if (!isset($versionid) || $versionid === FALSE)
		{
			$versionid = $this->getLastVersionInfo("versionid");
		}
		
		$sql = "
				SELECT count(*) as hits, versionid
				FROM fileshare_stats
				WHERE elementid = '".$this->getElementId()."'
				GROUP BY versionid
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

	public function getSecondsSinceLastUpdate ()
	{
		$updatedate	= $this->getLastVersionInfo("timestamp");
		$nowdate	= mktime();
		return ($nowdate-$updatedate);
	}
	
	public function getType()
	{
		if ($this->getSysInfos("type") === FALSE)
		{
				if (is_file($this->fullpath))
				{
					return "file";
				}
				elseif (is_dir($this->fullpath))
				{
					return "folder";
				}
				else
				{
					return FALSE;
				}
		}
		else
		{
			return $this->getSysInfos("type");
		}
	}
	
	public function isFile ()
	{
		if ($this->getType() == "file" || is_file($this->fullpath) )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function isDirectory ()
	{

		//Ajout pour eviter la boucle avec this->getFullPath()
		preg_match("/(.*)[\/]{0,1}$/", $this->fullpath, $matches);
		$fullpath = $matches[0];

		if ($this->getType() == "folder" || is_dir($fullpath) )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	 * Vérifie si l'élément existe physiquement
	 */
	public function existsOnDisk ()
	{
		if (is_file($this->getFullPath()) || is_dir($this->getFullPath()))
		{
			return TRUE;
		}
		return FALSE;
	}
}

?>
