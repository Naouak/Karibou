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
 * default page
 * 
 * @package applications
 */
abstract class CalendarModel extends Model
{
	protected function buildCalendar(KDate $currentDate)
	{
		$firstDayOfTheMonth = new Date($currentDate);
		$firstDayOfTheMonth->setDay(1);
		
		$myCalendars = new CalendarListDB($this->db, $this->currentUser);
		$cals = $myCalendars->getSubscribedCalendars();
		$month_evts_ol = array();
		if (count($cals) > 0)
		{
			foreach($cals as $cal)
			{
				$month_evts_ol[] = $cal->getReader()->getEventsByMonth( $currentDate );
			}
		}
		$days = array();
		$i=0;
		
		$previousDay = $firstDayOfTheMonth;
		while($previousDay->getDayOfWeek() != 1)
		{
			$previousDay = $previousDay->getPrevDay();
			$days[$i] = array('date' => $previousDay);
			$i++;
		}
		$days = array_reverse($days);
		$nextDay = $firstDayOfTheMonth;
		while( ($currentDate->getMonth() == $nextDay->getMonth()) || 
			((sizeof($days) % 7 ) != 0) )
		{
			$nextDay_start = new Date($nextDay);
			$nextDay_start->setHour(0);
			$nextDay_start->setMinute(0);
			$nextDay_start->setSecond(0);
			$nextDay_stop = new Date($nextDay->getNextDay());
			$nextDay_stop->setHour(0);
			$nextDay_stop->setMinute(0);
			$nextDay_stop->setSecond(0);			
			
			$nextDay_evts = array();
			foreach($month_evts_ol as $month_evts)
			{
				$nextDay_evts = array_merge($nextDay_evts, $month_evts->getAllEvents($nextDay_start, $nextDay_stop) );
			}
			//Debug::display($nextDay_evts);
			$days[$i] = array('date' => $nextDay, 'events' => $nextDay_evts);
			$nextDay = $nextDay->getNextDay();
			$i++;
		}
		
		$title = $currentDate->getMonthName() . ' ' . $currentDate->getYear();
		$this->assign('days', $days);
		$this->assign('weekDayName', KDate::getWeekNameArray());
		$this->assign('currentDate', $currentDate);
		$this->assign('previousMonth', $currentDate->getPrevMonth());
		$this->assign('nextMonth', $currentDate->getNextMonth());
	}
}

?>