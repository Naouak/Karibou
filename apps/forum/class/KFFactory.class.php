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
class KFFactory
{
	protected $db;
	protected $userFactory;
	protected $profileFactory;
	
	protected $locationInfosSQLSelect;

	public function __construct(PDO $db, UserFactory $userFactory)
	{
		$this->db = $db;
		$this->userFactory = $userFactory;
		$this->geo = new Geo($db,$userFactory);
		
	}
	
	/* COMPANIES */
	public function getForumList()
	{
		$companies = array();
	
		$sql = "
				SELECT
					forum_forums.*
					COUNT(forum_messages.id) as nbmessages,
					TIMESTAMP(MAX(forum_messages.date)) as lastmessagedate
				FROM
					forum_forums
				LEFT OUTER JOIN forum_messages
					ON (forum_forums.id = forum_messages.forumid) && (forum_messages.deleted = 0) && (forum_messages.last = 1)
				GROUP BY
					forum_forums.id
				ORDER BY
					lastmessagedate
					DESC
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$infos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			
			if (count($infos)>0)
			{
				foreach ($infos as $info)
				{
				
					$threads[] = new KFForum($info, $this->userFactory);
				}
			}
			else
			{
			}
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		return $threads;
	}
	
	public function getForumById($threadid)
	{
		$sql = "
				SELECT
					forum_forums.*
					COUNT(forum_messages.id) as nbmessages,
					TIMESTAMP(MAX(forum_messages.date)) as lastmessagedate
				FROM
					forum_forums
				LEFT OUTER JOIN forum_messages
					ON (forum_forums.id = forum_messages.threadid) && (forum_messages.deleted = 0) && (forum_messages.last = 1)
				WHERE
					forum_forums.id = '$threadid'
				GROUP BY
					forum_forums.id
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$infos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			if (count($infos)>0)
			{
				$thread = new KFForum($infos[0],$this->userFactory);
				return $thread;
			}
			else
			{
				return FALSE;
			}
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		return FALSE;
	}

	protected function _getElementParams($element)
	{
		$params = array();
		if (get_class($element) == "KFForum")
		{
			$params["dbTable"]	= 'forum_forums';
			$params["dbFields"]	= array('name', 'description');
		}
		elseif (get_class($element) == "KFMessage")
		{
			$params["dbTable"] = 'forum_messages';
			$params["dbFields"] = array('subject', 'title');
		}
		retun $params;
	}

	public function saveElement ($element)
	{
		$params = $this->_getElementParams($element);
		$dbTable = $params["dbTable"];
		$dbFields = $params["dbFields"];

		if (($element->getInfo('id') !== FALSE) && ($element->getInfo('id') != ""))
		{
			$sqlu = "
					UPDATE $dbTable
					SET last = '0'
					WHERE	id = '".$element->getInfo('id')."'
				";
			try
			{
				$stmtu = $this->db->exec($sqlu);
				unset($stmtu);
				$elementid = $element->getInfo('id');
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		else
		{
			$sqlm = "
					SELECT MAX(id) as maxelementid
					FROM $dbTable
				";
				
			try
			{
				$stmtm = $this->db->query($sqlm);
				$maxelementid = $stmtm->fetchAll(PDO::FETCH_ASSOC);
				$elementid = $maxcompanyid[0]["maxelementid"] + 1;
				unset($stmtm);
				
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		
		if (isset($elementid))
		{
			$SQLFieldName = "";
			foreach ($dbFields as $dbField)
			{
				$SQLFieldsInsert .= ", $dbField";
				$SQLFieldsValues .= ", '".$element->getInfo($dbField)."'";
			}
		
			$currentUser = $this->userFactory->getCurrentUser();
			
			$sqlc = "
					INSERT INTO $dbTable
					(id, last, user_id, datetime $SQLFieldsInsert)
					VALUES
					('".$elementid."', 1, '".$currentUser->getId()."', NOW() $SQLFieldsValues)
				";
					
			try
			{
				$stmtc = $this->db->exec($sqlc);
				unset($stmtc);
				return $companyid;
				
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	public function deleteElement ($elementid, $dbTable)
	{
		$sqlu = "
				UPDATE $dbTable
				SET deleted = '1', datetime = NOW()
				WHERE
					id = '".$elementid."'
				AND
					last = 1
			";
			
		try
		{
			$stmtu = $this->db->exec($sqlu);
			unset($stmtu);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
	}
}

?>