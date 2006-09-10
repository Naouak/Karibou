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
class CalendarEventList extends ObjectList
{
	function getEventsOfDay(Date $date)
	{
		$start = new Date($date);
		$start->setHour(0);
		$start->setMinute(0);
		$start->setSecond(0);
		$stop = $date->getNextDay();
		$stop->setHour(0);
		$stop->setMinute(0);
		$stop->setSecond(0);
		return $this->getEvents($start, $stop);
	}

	function getEvents(Date $start, Date $stop)
	{
		$out = array();
		foreach($this->data as $event)
		{
			if( $evt = $event->getEvent($start, $stop) )
			{
				$out[] = $evt;
			}
		}
		return $out;
	}
	
	function testEvents(Date $start, Date $stop)
	{
		$out = array();
		foreach($this->data as $event)
		{
			if( $event->testEvent($start, $stop) )
			{
				$out[] = $event;
			}
		}
		return $out;
	}
	
	function getAllEvents(Date $start, Date $stop)
	{
		$out = array();
		foreach($this->data as $event)
		{
			if( $evt = $event->getAllEvent($start, $stop) )
			{
				$out[] = $evt;
			}
		}
		return $out;
	}
	
}

?>