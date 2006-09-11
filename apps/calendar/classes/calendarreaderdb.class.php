<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * @package applications
 */
class CalendarReaderDB extends CalendarReader
{
	protected $id;
	protected $db;
	
	function __construct(PDO $db, $id)
	{
		$this->db = $db;
		$this->id = $id;
	}
	
	function getEventsByDay(Date $date)
	{
		$qry = "SELECT ce.* FROM calendar c, calendar_event ce WHERE c.id=ce.calendar_id AND c.id='".$this->id."'";
		$qry .= " AND (" ;
		$qry .= " ((EXTRACT(DAY FROM '".$date->getDate()."') >= EXTRACT(DAY FROM ce.startdate))";
		$qry .= "  AND (EXTRACT(DAY FROM '".$date->getDate()."') <= EXTRACT(DAY FROM ce.stopdate)) )";
		$qry .= " AND " ;
		$qry .= " ((EXTRACT(MONTH FROM '".$date->getDate()."') >= EXTRACT(MONTH FROM ce.startdate))";
		$qry .= "  AND (EXTRACT(MONTH FROM '".$date->getDate()."') <= EXTRACT(MONTH FROM ce.stopdate)) )";
		$qry .= " AND " ;
		$qry .= " ((EXTRACT(YEAR FROM '".$date->getDate()."') >= EXTRACT(YEAR FROM ce.startdate))";
		$qry .= "  AND (EXTRACT(YEAR FROM '".$date->getDate()."') <= EXTRACT(YEAR FROM ce.stopdate)) )";
		$qry .= " OR ce.recurrence != '' ) ORDER BY HOUR(ce.startdate), MINUTE(ce.startdate)" ;

		try
		{
			$stmt = $this->db->query($qry);
		}
		catch(PDOException $e)
		{
			Debug::kill( $qry." : ".$e->getMessage() );
		}
		$out = new CalendarEventList();
		while( $tab = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$out[] = new CalendarEventDB($tab);
		}
		unset($stmt);
		return $out;
	}
	
	function getEventsByWeek(Date $date)
	{
		$qry = "SELECT ce.* FROM calendar c, calendar_event ce WHERE c.id=ce.calendar_id AND c.id='".$this->id."'";
		$qry .= " AND (" ;
		$qry .= " (AND (EXTRACT(WEEK FROM '".$date->getDate()."') >= EXTRACT(WEEK FROM ce.startdate))";
		$qry .= "  AND (EXTRACT(WEEK FROM '".$date->getDate()."') <= EXTRACT(WEEK FROM ce.stopdate)) )";
		$qry .= " AND " ;
		$qry .= " (AND (EXTRACT(YEAR FROM '".$date->getDate()."') >= EXTRACT(YEAR FROM ce.startdate))";
		$qry .= "  AND (EXTRACT(YEAR FROM '".$date->getDate()."') <= EXTRACT(YEAR FROM ce.stopdate)) )";
		$qry .= " OR ce.recurrence != '' )" ;

		try
		{
			$stmt = $this->db->query($qry);
		}
		catch(PDOException $e)
		{
			Debug::kill( $e->getMessage() );
		}
		$out = new CalendarEventList();
		while( $tab = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$out[] = new CalendarEventDB($tab);
		}
		unset($stmt);
		return $out;
	}
	
	function getEventsByMonth(Date $date)
	{
		$qry = "SELECT ce.* FROM calendar c, calendar_event ce WHERE c.id=ce.calendar_id AND c.id='".$this->id."'";
		$qry .= " AND (" ;
		$qry .= " ((EXTRACT(MONTH FROM '".$date->getDate()."') >= EXTRACT(MONTH FROM ce.startdate))";
		$qry .= "  AND (EXTRACT(MONTH FROM '".$date->getDate()."') <= EXTRACT(MONTH FROM ce.stopdate)) )";
		$qry .= " AND " ;
		$qry .= " ((EXTRACT(YEAR FROM '".$date->getDate()."') >= EXTRACT(YEAR FROM ce.startdate))";
		$qry .= "  AND (EXTRACT(YEAR FROM '".$date->getDate()."') <= EXTRACT(YEAR FROM ce.stopdate)) )";
		$qry .= " OR ce.recurrence != '' )" ;

		try
		{
			$stmt = $this->db->query($qry);
		}
		catch(PDOException $e)
		{
			Debug::kill( $qry." : ".$e->getMessage() );
		}
		$out = new CalendarEventList();
		while( $tab = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$out[] = new CalendarEventDB($tab);
		}
		unset($stmt);
		return $out;
	}
	
	function getId()
	{
		return $this->id;
	}
	
}

?>