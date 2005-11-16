<?php
/**
 * @copyright 2005 JoN
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package lib
 **/

class Date_Span
{
	public $day = 0;
	public $hour = 0;
	public $minute = 0;
	public $second = 0;
	
	function setFromDateDiff(Date $date1, Date $date2)
	{
		if( Date::compare($date1, $date2) == 0 )
		{
			$this->second = 0;
		}
		else if( Date::compare($date1, $date2) > 0 )
		{
			$this->setFromSeconds($date1->getTime() - $date2->getTime());
		}
		else
		{
			$this->setFromSeconds($date2->getTime() - $date1->getTime());
		}
	}
	
	function setFromSeconds($seconds)
	{
		$this->day = floor($seconds/(3600*24));
		$seconds -= $this->day * (3600*24);
		$this->hour = floor($seconds/(3600));
		$seconds -= $this->hour * (3600);
		$this->minute = floor($seconds/(60));
		$seconds -= $this->minute * (60);
		$this->second = $seconds;
	}
	
	function toSeconds()
	{
		return ($this->day*24*3600)+($this->hour*3600)+($this->minute*60)+$this->second;
	}

	function toDays()
	{
		return $this->day*24*3600;
	}
}

?>