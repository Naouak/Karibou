<?php
/**
 * @copyright 2008 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * This is only a guessed content, nothing certain here...
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
		if(isset($this->args['week']) && $this->args['week'] != '')
		{
			$currentDate->setWeek($this->args['week']);
		}

		$this->buildCalendar($currentDate);
	}
}

?>
