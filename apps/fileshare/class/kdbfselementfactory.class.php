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
class KDBFSElementFactory
{
	protected $files = array();
	
	protected $db;
	protected $userFactory;
	protected $permission;

	function __construct(PDO $db, UserFactory $userFactory, $permission)
	{
	
		$this->db = $db;
		$this->userFactory = $userFactory;
		$this->permission = $permission;
	}

	public function getLastAddedFiles()
	{
		$sql = "
			SELECT	fileshare_sysinfos.id, fileshare_sysinfos.parent, fileshare_sysinfos.name, fileshare_sysinfos.creator, fileshare_versions.user,
					fileshare_versions.description, fileshare_versions.versionid, fileshare_versions.datetime, UNIX_TIMESTAMP(fileshare_versions.datetime) AS timestamp, fs_stats.hits
			FROM fileshare_versions
				LEFT JOIN fileshare_sysinfos ON fileshare_sysinfos.id = fileshare_versions.id
				LEFT JOIN (SELECT elementid, versionid, count( id ) AS hits FROM fileshare_stats GROUP BY elementid, versionid) AS fs_stats
					ON fs_stats.elementid = fileshare_versions.id AND fs_stats.versionid = fileshare_versions.versionid
			WHERE 	fileshare_sysinfos.deleted = 0 
				AND fileshare_sysinfos.type = 'file'
			ORDER BY fileshare_versions.datetime DESC 
			LIMIT 0 , 3
			";
			
		try
		{
			$stmt = $this->db->query($sql);
			$tab = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			
			$files = array();
			foreach ($tab as $data)
			{
				$files[] = new KFile($this->db, $this->userFactory, $this->permission, FALSE, FALSE, $data["id"], $data);
			}
			return $files;
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
	}
	
	public function getMostDownloadedFiles()
	{
		$sql = "
			SELECT	fileshare_sysinfos.id, fileshare_sysinfos.parent, fileshare_sysinfos.name, fileshare_sysinfos.creator, fileshare_versions.user,
					fileshare_versions.description, fileshare_versions.versionid, fileshare_versions.datetime, UNIX_TIMESTAMP(fileshare_versions.datetime) AS timestamp, fs_stats.hits
			FROM fileshare_versions
				LEFT JOIN fileshare_sysinfos ON fileshare_sysinfos.id = fileshare_versions.id
				LEFT JOIN (SELECT elementid, versionid, count( id ) AS hits FROM fileshare_stats GROUP BY elementid, versionid) AS fs_stats
					ON fs_stats.elementid = fileshare_versions.id AND fs_stats.versionid = fileshare_versions.versionid
			WHERE 	fileshare_sysinfos.deleted = 0 
				AND fileshare_sysinfos.type = 'file'
				AND fs_stats.hits IS NOT NULL
			ORDER BY fs_stats.hits DESC 
			LIMIT 0 , 3
			";
			
		try
		{
			$stmt = $this->db->query($sql);
			$tab = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			
			$files = array();
			foreach ($tab as $data)
			{
				$files[] = new KFile($this->db, $this->userFactory, $this->permission, FALSE, FALSE, $data["id"], $data);
			}
			return $files;
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
	}

	public function move ($elementid, $destinationid)
	{
		//Get elementid
		$file = new KFile ($this->db, $this->userFactory, $this->permission, FALSE, FALSE, $elementid);
		
		if ($destinationid == "")
		{
			$destination = new KDirectory ($this->db, $this->userFactory, $this->permission, "");			
			$set = "SET parent = NULL";
		}
		else
		{
			$destination = new KDirectory ($this->db, $this->userFactory, $this->permission, FALSE, FALSE, $destinationid);
			$set = "SET parent = '".$destination->getElementId()."'";
		}

		//Get destination path
		if ($destination->canUpdate() && $file->canWrite())
		{
			$from = $file->getFullPath();
			$to = $destination->getFullPath()."/".$file->getName();
			//Move file physically
			if(!is_file($to) && rename($from, $to))
			{
				//Move file in DB
				
				$sql = "
					UPDATE fileshare_sysinfos
					$set
					WHERE id = '".$file->getElementId()."'
					";
					
				try
				{
					$stmt = $this->db->exec($sql);
					unset($stmt);
				}
				catch(PDOException $e)
				{
					Debug::kill($e->getMessage());
				}
			}
			else
			{
				
			}
		}
		return new KFile($this->db, $this->userFactory, $this->permission, FALSE, FALSE, $elementid);
	}
}

?>