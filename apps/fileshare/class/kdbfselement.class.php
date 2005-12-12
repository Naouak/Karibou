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
	
	protected $path;
	
	protected $sysinfos;
	protected $rights;
	protected $versions;

	function __construct ($db, $type, $path)
	{
		$this->db			= $db;

		if ($this->db !== FALSE)
		{
			$this->path		= $path;
			
			$this->sysinfos	= array();
			$this->rights		= array();
			$this->versions	= array();
			
			$this->getAllInfos();
		}
	}

	function getAllInfos ()
	{
		$this->sysinfos = $this->getSysInfos();
		$this->id = $this->sysinfos['id'];
		$this->rights = $this->getRights();
		$this->versions = $this->getVersions();
		
	}

	//Method setting the $this->sysinfos var
	function getSysInfos ()
	{
		$sql = "
				SELECT *
				FROM fileshare_sysinfos
				WHERE	fileshare_sysinfos.path = '".$this->path."'
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
	function getRights ()
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
		
		$tab = $stmt->fetch(PDO::FETCH_ASSOC);
		
		unset($stmt);
		
		return $tab;
	}
	
	//Method setting the $this->versions var
	function getVersions ()
	{
		$sql = "
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
		
		$tab = $stmt->fetch(PDO::FETCH_ASSOC);
		
		unset($stmt);
		
		return $tab;
	}
	
}

?>