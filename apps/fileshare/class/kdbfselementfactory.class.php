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

	function __construct(PDO $db, UserFactory $userFactory)
	{
	
		$this->db = $db;
		$this->userFactory = $userFactory;
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
				$files[] = new KFile($this->db, $this->userFactory, FALSE, FALSE, $data["id"], $data);
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
				$files[] = new KFile($this->db, $this->userFactory, FALSE, FALSE, $data["id"], $data);
			}
			return $files;
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
	}
	/*
	public function getLastAddedFiles ()
	{
		$this->setLastAddedFiles();
		return $this->files;
	}
	*/
}

?>