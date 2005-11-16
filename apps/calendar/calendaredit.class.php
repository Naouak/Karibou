<?php

/**
 * @copyright 2005 Jonathan Semczyk <http://jon.netcv.org>
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/


class CalendarEdit extends Model
{
	public function build()
	{
 		if (isset($this->args['calendarid']) && $this->args['calendarid'] != '')
		{
			//Mode edition
			$calendarList = new CalendarListDB($this->db, $this->currentUser);
			$list = $calendarList->getAdminCalendars();
			foreach($list as $cal)
			{
				if( $cal->getId() == $this->args['calendarid'])
				{
					$this->assign("calendar", $cal);
					break;
				}
			}
		}
		else
		{
			//Mode creation
			$grouplist = $this->currentUser->getAllAdminGroups($this->db);
			$this->assign("grouplist", $grouplist);		
			
		}
		//Verification de la presence d'erreur et affection du message d'erreur a afficher
		$this->assign("calendarMessages", $this->formMessage->getSession());
		$this->formMessage->flush();
	}

}

?>
