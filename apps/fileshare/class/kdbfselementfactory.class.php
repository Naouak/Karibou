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

	public function getFilesFromSearch($keywords, $num = FALSE, $page = FALSE)
	{
		if ($num === FALSE)
		{
			$num = 20;
		}
		
		if ($page === FALSE)
		{
			$page = 0;
		}
		else
		{
			$page = $page - 1;
		}
		
		/*
SELECT fileshare_sysinfos.id, fileshare_sysinfos.parent, fileshare_sysinfos.name AS name, fileshare_sysinfos.creator, fileshare_versions.user, fileshare_versions.description AS description, fileshare_versions.versionid, fileshare_versions.datetime, UNIX_TIMESTAMP( fileshare_versions.datetime ) AS timestamp, fs_stats.hits,
MATCH (
name
)
AGAINST (
'test'
),
MATCH (
fileshare_versions.description
)
AGAINST (
'test'
)
FROM fileshare_versions
LEFT JOIN fileshare_sysinfos ON fileshare_sysinfos.id = fileshare_versions.id
LEFT JOIN (

SELECT elementid, versionid, count( id ) AS hits
FROM fileshare_stats
GROUP BY elementid, versionid
) AS fs_stats ON fs_stats.elementid = fileshare_versions.id
AND fs_stats.versionid = fileshare_versions.versionid
LIMIT 0 , 30
		*/
		
		$sql = "
			SELECT	fileshare_sysinfos.id, fileshare_sysinfos.parent, fileshare_sysinfos.name as name, fileshare_sysinfos.creator, fileshare_versions.user,
					fileshare_versions.description as description, fileshare_versions.versionid, fileshare_versions.datetime, UNIX_TIMESTAMP(fileshare_versions.datetime) AS timestamp, fs_stats.hits,
					MATCH(name) AGAINST ('".$keywords."') AS score1,
					MATCH(description) AGAINST ('".$keywords."') AS score2,
					(MATCH(name) AGAINST ('".$keywords."') + MATCH(description) AGAINST ('".$keywords."')) AS score
			FROM fileshare_versions
				LEFT JOIN fileshare_sysinfos ON fileshare_sysinfos.id = fileshare_versions.id
				LEFT JOIN (SELECT elementid, versionid, count( id ) AS hits FROM fileshare_stats GROUP BY elementid, versionid) AS fs_stats
					ON fs_stats.elementid = fileshare_versions.id AND fs_stats.versionid = fileshare_versions.versionid
			WHERE 	fileshare_sysinfos.deleted = 0 
				AND fileshare_sysinfos.type = 'file'
				AND (
					(MATCH(name) AGAINST ('".$keywords."')) > 0
					OR (MATCH(description) AGAINST ('".$keywords."')) > 0
					)
			GROUP BY fileshare_versions.id
			ORDER BY score DESC 
			LIMIT ".($page*$num)." , ".(($page+1)*$num)."
			";
			//AND score > 0
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
			echo "<br /><strong>";
			var_dump($e->getMessage());
			Debug::kill($e->getMessage());
		}
	}

	public function getLastAddedFiles($nb = FALSE)
	{
		if ($nb === FALSE)
		{
			$nb = 3;
		}
		
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
			LIMIT 0 , $nb
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
	
	public function getMostDownloadedFiles($nb = FALSE)
	{
	
		if ($nb === FALSE)
		{
			$nb = 3;
		}
		
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
			LIMIT 0 , $nb
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

	public function rename ($elementid, $name, $description, $versionid = FALSE) {
		//Get elementid
		$element = new KDBFSElement ($this->db, $this->userFactory, $this->permission, FALSE, FALSE, $elementid);
		$name = KText::epureString($name);
		if ($versionid == FALSE)
		{
			$versionid = $element->getLastVersionInfo('versionid');
		}
		
		$elementpath = KARIBOU_PUB_DIR.'/fileshare/share'.$element->getParentPath().'/'.$name;
		
		if ($name != "" && $description != "" && $element->canWrite() )
		{
			$sql = "
				UPDATE fileshare_versions
				SET description = '".$description."'
				WHERE id = '".$element->getElementId()."'
				AND
				versionid = '$versionid'
				";
			
			try
			{
				$stmt = $this->db->exec($sql);
				unset($stmt);
				//return new KDBFSElement ($this->db, $this->userFactory, $this->permission, FALSE, FALSE, $elementid);
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
			
			if (!is_file($elementpath) && !is_dir($elementpath))
			{
				if (rename($element->getFullPath(), $elementpath))
				{
					$sql = "
						UPDATE fileshare_sysinfos
						SET name = '".$name."'
						WHERE id = '".$element->getElementId()."'
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
			}
			
			return new KDBFSElement ($this->db, $this->userFactory, $this->permission, FALSE, FALSE, $elementid);
		}
		
		return FALSE;
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