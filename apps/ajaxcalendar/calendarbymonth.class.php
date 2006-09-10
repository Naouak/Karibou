<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2005 Benoit Tirmarche <benoit.tirmarche@telecomlille.net>
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
class CalendarByMonth extends CalendarModel
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
}

?>