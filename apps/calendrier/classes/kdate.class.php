<?php

/**
 * @version $Id: kdate.class.php,v 1.2 2004/12/03 00:52:56 jon Exp $
 * @copyright 2005 Benoit Tirmarche <benoit.tirmarche@telecomlille.net>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

//require_once 'Date.php';

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
		return array(
					0 => array('name' => 'Monday', 'abbr' => 'Mon'),
					1 => array('name' => 'Tuesday', 'abbr' => 'Tue'),
					2 => array('name' => 'Wednesday', 'abbr' => 'Wed'),
					3 => array('name' => 'Thursday', 'abbr' => 'Thu'),
					4 => array('name' => 'Friday', 'abbr' => 'Fri'),
					5 => array('name' => 'Saturday', 'abbr' => 'Sat'),
					6 => array('name' => 'Sunday', 'abbr' => 'Sun'),
					);
	}
}

?>