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
		$this->rights	= $rights;
		$this->versions	= $versions;
		
		$this->writeAllInfos();

	}

	function writeAllInfos ()
	{
		$this->writeSysInfos();
		if (count ($this->rights) > 0)
		{
			$this->writeRights();
		}
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


		//groupwner is null if there is no group owner
		if (is_null($this->sysinfos["groupowner"]))
		{
			$groupownertxt = "NULL";
		}
		else
		{
			$groupownertxt= "'".$this->sysinfos["groupowner"]."'";
		}
		
		//parent is the id of the parent folder
		if (is_null($this->sysinfos["parent"]))
		{
			$parenttxt = "NULL";
		}
		else
		{
			$parenttxt = "'".$this->sysinfos["parent"]."'";
		}

		$sql = "
				INSERT INTO fileshare_sysinfos
					(`id`, `parent`, `name`, `creator`, `groupowner`, `type`, `datetime`)
				VALUES
					('".$this->id."', $parenttxt, '".$this->sysinfos["name"]."', '".$this->sysinfos["creator"]."', $groupownertxt,'".$this->sysinfos["type"]."', NOW())
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
		if (is_null($this->rights["group"]))
		{
			$grouptxt = "NULL";
		}
		else
		{
			$grouptxt = "'".$this->rights["group"]."'";
		}
	
		$sql = "
				INSERT INTO fileshare_rights
					(`id`, `group`, `rights`, `datetime`)
				VALUES
					('".$this->id."', $grouptxt, '".$this->rights["rights"]."', NOW())
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
					(`id`, `description`, `user`, `datetime`)
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