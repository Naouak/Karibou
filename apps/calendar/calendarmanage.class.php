 <?php

/**
 * @copyright 2005 Jonathan Semczyk <http://jon.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/


class CalendarManage extends Model
{
	public function build()
	{
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "manage") );
		
		$myCalendars = new CalendarListDB($this->db, $this->currentUser);
		$cals_pub_tmp = $myCalendars->getPublicCalendars();
		$cals_subs = $myCalendars->getSubscribedCalendars();
		
		$cals_pub = array();
		foreach($cals_pub_tmp as $pubcal)
		{
			$insert = true;
			foreach( $cals_subs as $cal )
			{
				if( $cal->getId() == $pubcal->getId() )
				{
					$insert = false;
					break;
				}
			}
			if( $insert ) $cals_pub[] = $pubcal;
		}
		$cals_admin = $myCalendars->getAdminCalendars();
		
		$this->assign("public_cals", $cals_pub);
		$this->assign("subscribed_cals", $cals_subs);
		$this->assign("admin_cals", $cals_admin);
	}

}

?>
