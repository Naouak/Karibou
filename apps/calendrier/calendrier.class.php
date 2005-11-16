<?php 

/**
 * @version $Id: calendrier.class.php,v 1.2 2004/12/03 00:52:56 jon Exp $
 * @copyright 2005 Benoit Tirmarche <benoit.tirmarche@telecomlille.net>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class Calendrier extends Model
{
	public function build()
	{
		$currentDate = new KDate();
		if(isset($this->args['year']) && $this->args['year'] != '')
		{
			$currentDate->setYear($this->args['year']);
		}
		if(isset($this->args['month']) && $this->args['month'] != '')
		{
			$currentDate->setMonth($this->args['month']);
		}

		$this->buildCalendar($currentDate->getMonth(),$currentDate->getYear());
	}

	protected function buildCalendar($month, $year)
	{
		$currentDate = new KDate();
		$currentDate->setMonth($month);
		$currentDate->setYear($year);

		$firstDayOfTheMonth = $currentDate;
		$firstDayOfTheMonth->setDay(1);
		
		$days = array();
		$i=0;
		
		$previousDay = $firstDayOfTheMonth;
		while($previousDay->getPrevDay()->getDayOfWeek() !=0)
		{
			$previousDay = $previousDay->getPrevDay();
			$days[$i] = $previousDay;
			$i++;
		}
		$days = array_reverse($days);
		$nextDay = $firstDayOfTheMonth;
		while(sizeof($days) < 42)
		{
			$days[$i] = $nextDay;
			$nextDay = $nextDay->getNextDay();
			$i++;
		}

		$title = $currentDate->getMonthName() . ' ' . $currentDate->getYear();
		
		$this->assign('day', $days);
		$this->assign('weekDayName', KDate::getWeekNameArray());
		$this->assign('currentMonth', $currentDate);
		$this->assign('previousMonth', $currentDate->getPrevMonth());
		$this->assign('nextMonth', $currentDate->getNextMonth());
	}
}



?>