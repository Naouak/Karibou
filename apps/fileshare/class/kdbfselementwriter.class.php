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
class KDBFSElementWriter
{
	protected $db;
	
	protected $path;
	
	protected $sysinfos;
	protected $rights;
	protected $versions;

	function __construct ($db, $sysinfos, $rights, $versions)
	{
		$this->db	= $db;
		
		$this->sysinfos	= $sysinfos;
		$this->rights		= $rights;
		$this->versions	= $versions;
		
		$this->writeAllInfos();

	}

	function writeAllInfos ()
	{
		$this->writeSysInfos();
		$this->writeRights();
		$this->writeVersions();
	}

	//Method setting the $this->sysinfos var
	function writeSysInfos ()
	{
		$sql = "
				SELECT max(id) as max
				FROM fileshare_sysinfos
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
		$this->id = $tab[0]['max']+1;
		
		$sql = "
				INSERT INTO fileshare_sysinfos
					(`id`, `path`, `creator`, `owner`, `type`)
				VALUES
					('".$this->id."', '".$this->sysinfos["path"]."', '".$this->sysinfos["creator"]."', '".$this->sysinfos["owner"]."','".$this->sysinfos["type"]."')
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

	//Method setting the $this->rights var
	function writeRights ()
	{
		$sql = "
				INSERT INTO fileshare_rights
					(`id`, `group`, `rights`)
				VALUES
					('".$this->id."', '".$this->rights["group"]."', '".$this->rights["rights"]."')
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
	
	//Method setting the $this->versions var
	function writeVersions ()
	{
		$sql = "
				INSERT INTO fileshare_versions
					(`id`, `description`, `user`, `date`)
				VALUES
					('".$this->id."', '".$this->versions["description"]."', '".$this->versions["user"]."', NOW())
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
	
}

?>