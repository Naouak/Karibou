<?php
/**
 * @copyright 2005 Jonathan Semczyk <http://jon.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * @package applications
 */
class CalendarWriterDB extends CalendarWriter
{
	protected $id;
	protected $db;
	
	function __construct(PDO $db, $id)
	{
		$this->db = $db;
		$this->id = $id;
	}
	
	function addEvent(CalendarEvent $event)
	{
		$qry = "INSERT INTO calendar_event
				(calendar_id, author_id, description, summary, priority, startdate, stopdate, location, category, recurrence)
			VALUES
				(
				'".$this->id."',
				'".$event->authorid."',
				'".$event->description."',
				'".$event->summary."',
				'".$event->priority."',
				'".$event->startdate."',
				'".$event->stopdate."',
				'".$event->location."',
				'".$event->category."',
				'".$event->recurrence."' )";
		try
		{
			$this->db->exec($qry);
			$event->uid = $this->db->lastInsertId();
		}
		catch(PDOException $e)
		{
			Debug::kill( $e->getMessage() );
		}
	}
	
	function updateEvent(CalendarEvent $event)
	{
		$qry = "UPDATE calendar_event
				  SET	description	= '".$event->description."',
						summary 		= '".$event->summary."',
						priority 	= '".$event->priority."',
						startdate 	= '".$event->startdate."',
						stopdate		= '".$event->stopdate."',
						location		= '".$event->location."',
						category		= '".$event->category."',
						recurrence	= '".$event->recurrence."'
				  WHERE calendar_id = '".$this->id."' AND id = '".$event->uid."'";
		try
		{
			$this->db->exec($qry);
		}
		catch(PDOException $e)
		{
			Debug::kill( $e->getMessage() );
		}
	}
	
	function deleteEvent(CalendarEvent $event)
	{
		$qry = "DELETE FROM calendar_event
      			  WHERE calendar_id = '".$this->id."' AND id = '".$event->uid."'";
		try
		{
			if ($this->db->exec($qry))
            {
              return TRUE;
            }
            else
            {
              return FALSE;
            }
		}
		catch(PDOException $e)
		{
			Debug::kill( $e->getMessage() );
		}
	}
}

?>
