<?php
/**
 * @copyright 2005 Benoit Tirmarche <benoit.tirmarche@telecomlille.net>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class KDate extends Date
{
	function getPrevMonth()
	{
		$prevMonth = new KDate();
		$prevMonth->copy($this);

		if($prevMonth->getMonth() == 1)
		{
			$prevMonth->setMonth(12);
			$prevMonth->setYear($prevMonth->getYear() - 1);
		}
		else
		{
			$prevMonth->setMonth($prevMonth->getMonth() - 1);
		}
		return $prevMonth;
	}

	function getNextMonth()
	{
		$nextMonth = new KDate();
		$nextMonth->copy($this);

		if($nextMonth->getMonth() == 12)
		{
			$nextMonth->setMonth(1);
			$nextMonth->setYear($nextMonth->getYear() + 1);
		}
		else
		{
			$nextMonth->setMonth($nextMonth->getMonth() + 1);
		}
		return $nextMonth;
	}
	
	function getWeekNameArray()
	{
		$day0 = mktime(0, 0, 0, 10, 3, 2005);
		$day1 = $day0 + 86400;
		$day2 = $day0 + 172800;
		$day3 = $day0 + 259200;
		$day4 = $day0 + 345600;
		$day5 = $day0 + 432000;
		$day6 = $day0 + 518400;
		return array(
					0 => array('name' => strftime("%A", $day0), 'abbr' => strftime('%a', $day0)),
					1 => array('name' => strftime("%A", $day1), 'abbr' => strftime('%a', $day1)),
					2 => array('name' => strftime("%A", $day2), 'abbr' => strftime('%a', $day2)),
					3 => array('name' => strftime("%A", $day3), 'abbr' => strftime('%a', $day3)),
					4 => array('name' => strftime("%A", $day4), 'abbr' => strftime('%a', $day4)),
					5 => array('name' => strftime("%A", $day5), 'abbr' => strftime('%a', $day5)),
					6 => array('name' => strftime("%A", $day6), 'abbr' => strftime('%a', $day6)),
					);
	}
}

?>