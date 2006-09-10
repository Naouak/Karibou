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
abstract class CalendarReader
{

	abstract function getEventsByDay(Date $date);
	abstract function getEventsByWeek(Date $date);
	abstract function getEventsByMonth(Date $date);

}

?>