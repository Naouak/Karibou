<?php
/**
 * @copyright 2005 JoN
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package lib
 **/

class Date
{
	/**
		Array
		(
		    [seconds] => 40
		    [minutes] => 58
		    [hours]   => 21
		    [mday]    => 17
		    [wday]    => 2
		    [mon]     => 6
		    [year]    => 2003
		    [yday]    => 167
		    [weekday] => Tuesday
		    [month]   => June
		    [0]       => 1055901520
		)
	*/
    protected $data;
    
	function __construct($date = null)
	{
		if( is_null($date) )
		{
			$this->data = getdate(time());
		}
		else if( is_a($date, 'Date') )
		{
			$this->copy($date);
		}
		else if( is_array($date) && isset($date[0]) )
		{
			$this->timecopy($date[0]);
		}
		else
		{
			$this->data = getdate(strtotime($date));
		}
	}
	
	function copy(Date $date)
	{
		$this->data = getdate($date->getTime());
	}
	
	function timecopy($timestamp)
	{
		$this->data = getdate($timestamp);
	}

	/*
	 * Get / Set functions
	 */
	
	function getTime()
	{
		return $this->data[0];
	}
	function getDate($format="%Y-%m-%d %T")
	{
		return strftime($format, $this->getTime());
	}
	
	function getYear()
	{
		return $this->data['year'];
	}
	function setYear($year)
	{
		$time = mktime(
			$this->data['hours'],
			$this->data['minutes'],
			$this->data['seconds'],
			$this->data['mon'],
			$this->data['mday'],
			$year
			);
		$this->data = getdate($time);
	}
	function getMonth()
	{
		return $this->data['mon'];
	}
	function setMonth($mon)
	{
		$time = mktime(
			$this->data['hours'],
			$this->data['minutes'],
			$this->data['seconds'],
			$mon,
			$this->data['mday'],
			$this->data['year']
			);
		$this->data = getdate($time);
	}
	function getDay()
	{
		return $this->data['mday'];
	}
	function setDay($mday)
	{
		$time = mktime(
			$this->data['hours'],
			$this->data['minutes'],
			$this->data['seconds'],
			$this->data['mon'],
			$mday,
			$this->data['year']
			);
		$this->data = getdate($time);
	}
	function getHour()
	{
		return $this->data['hours'];
	}
	function setHour($hours)
	{
		$time = mktime(
			$hours,
			$this->data['minutes'],
			$this->data['seconds'],
			$this->data['mon'],
			$this->data['mday'],
			$this->data['year']
			);
		$this->data = getdate($time);
	}
	function getMinute()
	{
		return $this->data['minutes'];
	}
	function setMinute($minutes)
	{
		$time = mktime(
			$this->data['hours'],
			$minutes,
			$this->data['seconds'],
			$this->data['mon'],
			$this->data['mday'],
			$this->data['year']
			);
		$this->data = getdate($time);
	}
	function getSecond()
	{
		return $this->data['seconds'];
	}
	function setSecond($seconds)
	{
		$time = mktime(
			$this->data['hours'],
			$this->data['minutes'],
			$seconds,
			$this->data['mon'],
			$this->data['mday'],
			$this->data['year']
			);
		$this->data = getdate($time);
	}
	
	function getYearDay()
	{
		return $this->data['yday'];
	}
	
	function getDayOfWeek()
	{
		return $this->data['wday'];
	}
	function getWeekOfYear()
	{
		return strftime("%U", $this->getTime() );
	}
	
	function getPrevDay()
	{
		$newDate = new Date();
		$time = $this->getTime() - 3600*24;
		$newDate->timecopy($time);
		return $newDate;
	}
	function getNextDay()
	{
		$newDate = new Date();
		$time = $this->getTime() + 3600*24;
		$newDate->timecopy($time);
		return $newDate;
	}
	
	function getMonthName()
	{
		return $this->data['month'];
	}
	
	/*
	 * Compare functions
	 */
	
	function compare(Date $date1, Date $date2)
	{
		if( $date1->getTime() > $date2->getTime() )
		{
			return 1;
		}
		else if( $date1->getTime() < $date2->getTime() )
		{
			return -1;
		}
		return 0;
	}
	
	function before( Date $date )
	{
		if( Date::compare($this, $date) < 0 )
		{
			return TRUE;
		}
		return FALSE;
	}
	function after( Date $date )
	{
		if( Date::compare($this, $date) > 0 )
		{
			return TRUE;
		}
		return FALSE;
	}
	
	function isToday()
	{
		$today = new Date();
		return $this->isSameDay($today);
	}
	
	function isSameDay(Date $date)
	{
		if( ($this->getYear() == $date->getYear()) && ($this->getYearDay()==$date->getYearDay()) ) return true;
		return false;
	}
	
	/*
	 * Calc functions
	 */

	function addSeconds($sec)
	{
		$time = $this->getTime() + $sec;
		$this->timecopy($time);
	}
	function subtractSeconds($sec)
	{
		$time = $this->getTime() - $sec;
		$this->timecopy($time);
	}
	
	function addSpan(Date_Span $span)
	{
		$this->timecopy($this->getTime() + $span->toSeconds());
	}
	function subtractSpan(Date_Span $span)
	{
		$this->timecopy($this->getTime() - $span->toSeconds());
	}
}

?>