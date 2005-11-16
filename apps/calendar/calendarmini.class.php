<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class CalendarMini extends CalendarModel
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

		$this->buildCalendar($currentDate);
	}
}
?>
