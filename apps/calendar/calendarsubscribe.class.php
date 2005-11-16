<?php

/**
 * @copyright 2005 Jonathan Semczyk <http://jon.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/


class CalendarSubscribe extends FormModel
{
	public function build()
	{
		if( isset($_GET['id']) )
		{
			$myCalendars = new CalendarListDB($this->db, $this->currentUser);
			$public = $myCalendars->getPublicCalendars();
			$admin = $myCalendars->getAdminCalendars();
			$cals = array_merge($public, $admin);
			$ok_to_read = false;
			foreach($cals as $cal)
			{
				if( $cal->getId() == $_GET['id'] )
				{
					$ok_to_read = true;
					break;
				}
			}
			if( $ok_to_read )
			{
				if( ($subscribed = $this->currentUser->getPref("subscribed_calendars")) === FALSE )
				{
					$myCalendars = new CalendarListDB($this->db, $this->currentUser);
					$cals = $myCalendars->getDefaultCalendars();
					$subscribed = array();
					foreach($cals as $cal)
					{
						$subscribed[] = $cal->getId();
					}
				}
				$already_in_there = false;
				foreach($subscribed as $cal_id)
				{
					if( $cal_id == $_GET['id'] )
					{
						$already_in_there = true;
						break;
					}
				}
				if( ! $already_in_there )
				{
					$subscribed[] = $_GET['id'];
					$this->currentUser->setPref('subscribed_calendars', $subscribed);
				}
			}
		}
	}

}

?>
