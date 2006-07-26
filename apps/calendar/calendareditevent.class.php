 <?php

/**
 * @copyright 2006 Jonathan Semczyk <http://jon.netcv.org>
 * @copyright 2005 Benoit Tirmarche <benoit.tirmarche@telecomlille.net>
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/


class CalendarEditEvent extends Model
{
	public function build()
	{
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "addEvent") );
		
 		if (isset($this->args['eventid'],$this->args['calendarid']) && $this->args['eventid'] != '' && $this->args['calendarid'] != '')
		{
			//Mode edition
			$sql = "
				SELECT * FROM calendar_event WHERE calendar_id = ".$this->args['calendarid']." AND id = '".$this->args['eventid']."'
			";
	
			try
			{
				$stmt = $this->db->query($sql);
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
			
			if ( $tab = $stmt->fetchAll(PDO::FETCH_ASSOC) )
			{
				$event = new CalendarEventDB($tab[0]);
			}
			else
			{
			}

			$myCalendars = new CalendarListDB($this->db, $this->currentUser);
			$this->assign("calendars", $myCalendars->getAdminCalendars());

			$this->assign("calendarid", $this->args["calendarid"]);
			$this->assign("eventid", $this->args["eventid"]);

			if (isset($event)) {
				$this->assign ("event", $event);
				$this->assign("startdate", $event->startdate);
				$this->assign("stopdate", $event->stopdate);
			}
		
		}
		else
		{
			//Mode creation
			$startDate = new Date();
			$stopDate = new Date();

			if( isset($_POST['startyear']) && $_POST['startyear'] != '')
				$startDate->setYear($_POST['startyear']);
			if(isset($_POST['startmonth']) && $_POST['startmonth'] != '')
				$startDate->setMonth($_POST['startmonth']);
			if(isset($_POST['startday']) && $_POST['startday'] != '')
				$startDate->setDay($_POST['startday']);
			if(isset($_POST['starthour']) && $_POST['starthour'] != '')
				$startDate->setHour($_POST['starthour']);
			if(isset($_POST['startminute']) && $_POST['startminute'] != '')
				$startDate->setMinute($_POST['startminute']);

			if( isset($_POST['stopyear']) && $_POST['stopyear'] != '')
				$stopDate->setYear($_POST['stopyear']);
			if(isset($_POST['stopmonth']) && $_POST['stopmonth'] != '')
				$stopDate->setMonth($_POST['stopmonth']);
			if(isset($_POST['stopday']) && $_POST['stopday'] != '')
				$stopDate->setDay($_POST['stopday']);
			if(isset($_POST['stophour']) && $_POST['stophour'] != '')
				$stopDate->setHour($_POST['stophour']);
			if(isset($_POST['stopminute']) && $_POST['stopminute'] != '')
				$stopDate->setMinute($_POST['stopminute']);

/*
			if( isset($this->args['startyear']) && $this->args['startyear'] != '')
				$startDate->setYear($this->args['startyear']);
			if(isset($this->args['startmonth']) && $this->args['startmonth'] != '')
				$startDate->setMonth($this->args['startmonth']);
			if(isset($this->args['startday']) && $this->args['startday'] != '')
				$startDate->setDay($this->args['startday']);
			if(isset($this->args['starthour']) && $this->args['starthour'] != '')
				$startDate->setHour($this->args['starthour']);
			if(isset($this->args['startminute']) && $this->args['startminute'] != '')
				$startDate->setMinute($this->args['startminute']);

			if( isset($this->args['stopyear']) && $this->args['stopyear'] != '')
				$stopDate->setYear($this->args['stopyear']);
			if(isset($this->args['stopmonth']) && $this->args['stopmonth'] != '')
				$stopDate->setMonth($this->args['stopmonth']);
			if(isset($this->args['stopday']) && $this->args['stopday'] != '')
				$stopDate->setDay($this->args['stopday']);
			if(isset($this->args['stophour']) && $this->args['stophour'] != '')
				$stopDate->setHour($this->args['stophour']);
			if(isset($this->args['stopminute']) && $this->args['stopminute'] != '')
				$stopDate->setMinute($this->args['stopminute']);
*/
			$this->assign("startdate", $startDate->getDate() );
			$this->assign("stopdate", $stopDate->getDate() );


			$myCalendars = new CalendarListDB($this->db, $this->currentUser);
			$this->assign("calendars", $myCalendars->getAdminCalendars());

		}
		//Verification de la presence d'erreur et affection du message d'erreur a afficher
		$this->assign("calendarMessages", $this->formMessage->getSession());
		$this->formMessage->flush();
	}

}

?>
