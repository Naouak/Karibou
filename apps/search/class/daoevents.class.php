<?php 
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/** 
 * 
 * @package applications
 **/
class DAOEvents extends DAOElement
{
	protected $db;
	protected $userFactory;
	
	public function findFromKeyWords($keywords) {
		$myCalendars = new CalendarListDB($this->db, $this->userFactory->getCurrentUser());
		$cals = $myCalendars->getSubscribedCalendars();
		$evts_ol = new ObjectList();
		if (count($cals) > 0)
		{
			foreach($cals as $cal)
			{
				$evts_ol->merge($cal->getReader()->getEventsBySearch( $keywords ));
			}
		}
		return $evts_ol;
	}
}

?>