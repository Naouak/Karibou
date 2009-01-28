<?php
/**
 * @copyright 2005 Jonathan Semczyk <http://jon.netcv.org>
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * @package applications
 */
abstract class CalendarList
{
	protected $user;
	
	function __construct(CurrentUser $user)
	{
		$this->user = $user;
	}
	
	abstract function getDefaultCalendars();
	abstract function getCalendarById($id);
	abstract function getCalendars($array);
	abstract function getPublicCalendars();
	abstract function getAdminCalendars();
	
	function getSubscribedCalendars()
	{
		if( ($cals = $this->user->getPref("subscribed_calendars")) !== FALSE )
		{
			return $this->getCalendars($cals);
		}
		else
		{
			return $this->getDefaultCalendars();
		}
	}
}

?>