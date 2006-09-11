<?php

/**
 * @copyright 2005 Jonathan Semczyk <http://jon.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/


class CalendarUnsubscribe extends FormModel
{
	public function build()
	{
		if( isset($_GET['id']) )
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
			$new_subscribed = array();
			foreach($subscribed as $cal_id)
			{
				if( $cal_id != $_GET['id'] )
				{
					$new_subscribed[] = $cal_id;
				}
			}
			$this->currentUser->setPref('subscribed_calendars', $new_subscribed);
		}
	}

}

?>
