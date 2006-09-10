<?php
/**
 * @copyright 2005 Jonathan Semczyk <http://jon.netcv.org>
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * @package applications
 */
class CalendarListDB extends CalendarList
{
	protected $db;
	
	function __construct(PDO $db, CurrentUser $user)
	{
		parent::__construct($user);
		$this->db = $db;
	}
	
	function getDefaultCalendars()
	{
		$this->user->getGroups($this->db);
		$sql = "
			SELECT calendar.*, 'user' as caltype FROM calendar_user, calendar WHERE calendar_user.user_id = ".$this->user->getID()." AND calendar.id = calendar_user.calendar_id
			UNION
			SELECT calendar.*, 'group' as caltype FROM calendar_group, calendar WHERE group_id IN (".$this->user->getGroupTreeQuery().")  AND calendar.id = calendar_group.calendar_id";

		try
		{
			$stmt = $this->db->query($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		$calendars = array();
		while( $tab = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$reader = new CalendarReaderDB($this->db, $tab["id"]);
			$writer = new CalendarWriterDB($this->db, $tab["id"]);
			$calendars[] = new Calendar($reader, $writer, $tab["caltype"], $tab["id"], $tab["name"], $tab["type"]);
		}
		unset($stmt);
		return $calendars;
	}

	function getCalendarById($id)
	{
		$sql = "
			SELECT calendar.*, 'user' as caltype FROM calendar_user, calendar WHERE calendar.id = ".$id." AND calendar.id = calendar_user.calendar_id
			UNION
			SELECT calendar.*, 'group' as caltype FROM calendar_group, calendar WHERE calendar.id = ".$id."  AND calendar.id = calendar_group.calendar_id";

		try
		{
			$stmt = $this->db->query($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		if( $tab = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$reader = new CalendarReaderDB($this->db, $tab["id"]);
			$writer = new CalendarWriterDB($this->db, $tab["id"]);
			unset($stmt);
			return  new Calendar($reader, $writer, $tab["caltype"], $tab["id"], $tab["name"], $tab["type"]);
		}
		unset($stmt);
		return FALSE;
	}
	
	function getCalendars($array)
	{
		if( empty($array) )
		{
			return array();
		}
		$in = "";
		foreach($array as $id)
		{
			if( !empty($in) ) $in .= ", ";
			$in .= "'".$id."'";
		}
		
		$sql = "
			SELECT calendar.*, 'user' as caltype FROM calendar_user, calendar WHERE calendar.id IN (".$in.") AND calendar.id = calendar_user.calendar_id
			UNION
			SELECT calendar.*, 'group' as caltype FROM calendar_group, calendar WHERE calendar.id IN (".$in.")  AND calendar.id = calendar_group.calendar_id";

		try
		{
			$stmt = $this->db->query($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		$calendars = array();
		while( $tab = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$reader = new CalendarReaderDB($this->db, $tab["id"]);
			$writer = new CalendarWriterDB($this->db, $tab["id"]);
			$calendars[] = new Calendar($reader, $writer, $tab["caltype"], $tab["id"], $tab["name"], $tab["type"]);
		}
		unset($stmt);
		return $calendars;
	}
		
	function getPublicCalendars()
	{
		$sql = "
			SELECT calendar.*, 'user' as caltype FROM calendar_user, calendar WHERE calendar.id = calendar_user.calendar_id AND calendar.type='public'
			UNION
			SELECT calendar.*, 'group' as caltype FROM calendar_group, calendar WHERE calendar.id = calendar_group.calendar_id AND calendar.type='public'";

		try
		{
			$stmt = $this->db->query($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		$calendars = array();
		while( $tab = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$reader = new CalendarReaderDB($this->db, $tab["id"]);
			$writer = new CalendarWriterDB($this->db, $tab["id"]);
			$calendars[] = new Calendar($reader, $writer, $tab["caltype"], $tab["id"], $tab["name"], $tab["type"]);
		}
		unset($stmt);
		return $calendars;
	}
	
	function getAdminCalendars()
	{
		$sql = "
			SELECT calendar.*,  'user' as caltype FROM calendar_user, calendar WHERE calendar_user.user_id = ".$this->user->getID()." AND calendar.id = calendar_user.calendar_id
			UNION
			SELECT calendar.*, 'group' as caltype FROM calendar_group, calendar WHERE group_id IN (".$this->user->getGroupTreeAdminQuery().")  AND calendar.id = calendar_group.calendar_id";

		try
		{
			$stmt = $this->db->query($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		$calendars = array();
		while( $tab = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$reader = new CalendarReaderDB($this->db, $tab["id"]);
			$writer = new CalendarWriterDB($this->db, $tab["id"]);
			$calendars[] = new Calendar($reader, $writer, $tab["caltype"], $tab["id"], $tab["name"], $tab["type"]);
		}
		unset($stmt);
		Debug::display(print_r($calendars, true));
		return $calendars;		
	}

}

?>