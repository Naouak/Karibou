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
class CalendarEvent
{
	public $uid;
	public $calendarid;
	public $authorid;
	public $description;
	public $summary;
	public $priority;
	public $startdate;
	public $stopdate;
	public $location;
	public $category;
	public $recurrence;

	public $parent = FALSE;
	
	function __construct($uid, $calendarid, $authorid, $description, $summary, $priority, $startdate, $stopdate, $location, $category, $recurrence)
	{
		$this-> uid = $uid;
		$this-> calendarid = $calendarid;
		$this-> authorid = $authorid;
		$this-> description = $description;
		$this-> summary = $summary;
		$this-> priority = $priority;
		$this-> startdate = $startdate;
		$this-> stopdate = $stopdate;
		$this-> location = $location;
		$this-> category = $category;
		$this-> recurrence = $recurrence;
		
		$this->o_start = new KDate($startdate);
		$this->o_stop = new KDate($stopdate);
	}
	
	function __clone()
	{
		//Debug::kill("CLONE EVENT : ".$this->summary);
		$this->o_start = clone($this->o_start);
		$this->o_stop = clone($this->o_stop);
	}
	
	function getAllEvent(Date $start, Date $stop)
	{
		
		if( $this->recurrence == '' )
		{
			if($event = $this->getEvent($start, $stop))
				return $event;
		} 
		else
		{
			if( preg_match('/D1 #([0-9]+)/', $this->recurrence, $match ) )
			{
				// we assume the event is not overlapping
				$count = $match[1];

				if( ($count == 0) && $this->o_start->before($stop) )
				{ // infinite repeat
					$event = clone $this;
					$event->recurrence = '';
					$event->o_start->setDay($start->getDay());
					$event->o_start->setMonth($start->getMonth());
					$event->o_start->setYear($start->getYear());
					$event->o_stop->setDay($stop->getDay());
					$event->o_stop->setMonth($stop->getMonth());
					$event->o_stop->setYear($stop->getYear());
					$event  = $event->getEvent($start, $stop);
					return $event;
				}
			}
			else if( preg_match('/W1 #([0-9]+)/', $this->recurrence, $match ) )
			{
				// we assume the event is not overlapping
				$count = $match[1];

				if( ($count == 0) && $this->o_start->before($stop) )
				{ // infinite repeat
					$span = new Date_Span();
					$span->setFromDateDiff($end, $start);
					if( $span->day > 6 )
					{ // if end - start bigger than a week we display
						$start_weekday = $event->o_start->getWeekDay();
						$stop_weekday = $event->o_stop->getWeekDay();
						
						if( $start_weekday == $stop_weekday )
						{ // event on 1 day
							
						}
						else if( $start_weekday < $stop_weekday )
						{ // start / stop on same week
						}
						else
						{ // stop on next week
						}
					}
					else
					{ // need to find out if we are inside the span
					}
				}
			}
		}
		return FALSE;
	}
	
	function getEvent(Date $today, Date $tomorrow)
	{
		if ( ( Date::compare($today, $this->o_start) <= 0 ) &&
		     ( Date::compare($tomorrow, $this->o_start) >= 0 ) )
		{
			if ( ( Date::compare($today, $this->o_stop) <= 0) &&
			     ( Date::compare($tomorrow, $this->o_stop) >= 0 ) )
			{
				$this->parent = FALSE;
				return $this;
			}
			else
			{
				$end = new Date($tomorrow);
				$end->subtractSeconds(1);
				$event = clone $this;
				$event->o_stop = $end;
				$event->parent = TRUE;
				return $event;
			}
		}
		else
		{
			if ( ( Date::compare($today, $this->o_stop) <= 0 ) &&
			     ( Date::compare($tomorrow, $this->o_stop) > 0 ) )
			{
				$start = new Date($today);
				$event = clone $this;
				$event->o_start = $start;
				$event->parent = TRUE;
				return $event;
			}
		}
		if(( Date::compare($today, $this->o_start) > 0 ) &&
		    (Date::compare($tomorrow, $this->o_stop) <= 0 ) )
		{
			$start = new Date($today);
			$end = new Date($tomorrow);
			$end->subtractSeconds(1);
			$event = clone $this;
			$event->o_start = $start;
			$event->o_stop = $end;
			$event->parent = TRUE;
			return $event;
		}
		return FALSE;
	}
	
	function testEvent(Date $today, Date $tomorrow)
	{
		if ( ( Date::compare($today, $this->o_start) <= 0 ) &&
		     ( Date::compare($tomorrow, $this->o_start) >= 0 ) )
		{
			if ( ( Date::compare($today, $this->o_stop) <= 0) &&
			     ( Date::compare($tomorrow, $this->o_stop) >= 0 ) )
			{
				return TRUE;
			}
			else
			{
				return TRUE;
			}
		}
		else
		{
			if ( ( Date::compare($today, $this->o_stop) <= 0 ) &&
			     ( Date::compare($tomorrow, $this->o_stop) > 0 ) )
			{
				return TRUE;
			}
		}
		if(( Date::compare($today, $this->o_start) > 0 ) &&
		    (Date::compare($tomorrow, $this->o_stop) <= 0 ) )
		{
			return TRUE;
		}
		return FALSE;
	}
	
	function fromCalTime($caltime)
	{
		if( preg_match('/^([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2})([0-9]{2})([0-9]{2})(Z|\Z)$/i', $caltime, $match) )
		{
			$year = $match[1];
			$month = $match[2];
			$day = $match[3];
			$hour = $match[4];
			$min = $match[5];
			$sec = $match[6];
			
			return new Date(mktime($hour, $min, $sec, $month, $day, $year));
		}
		return FALSE;
	}
	
	function day($timestamp)
	{
		return (floor($timestamp / (3600*24))) * (3600*24);
	}
	
	function getDescriptionXHTML()
	{
        $wiki = new wiki2xhtmlBasic();
        // initialisation variables
        $wiki->wiki2xhtml();
        $content = $wiki->transform($this->description);
        return $content;
	}
	
	function getCalendarColor($n = FALSE)
	{
		return CalendarColors::getColor($this->calendarid, $n);
	}
}

?>
