 <?php

/**
 * @copyright 2005 Jonathan Semczyk <http://jon.netcv.org>
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
			}
		
		}
		else
		{
			//Mode creation
			if(isset($this->args['year']) && $this->args['year'] != '')
			{
				$currentDate->setYear($this->args['year']);
			}
			if(isset($this->args['month']) && $this->args['month'] != '')
			{
				$currentDate->setMonth($this->args['month']);
			}
			if(isset($this->args['day']) && $this->args['day'] != '')
			{
				$currentDate->setDay($this->args['day']);
			}
			
			$myCalendars = new CalendarListDB($this->db, $this->currentUser);
			$this->assign("calendars", $myCalendars->getAdminCalendars());

		}
		//Verification de la presence d'erreur et affection du message d'erreur a afficher
		$this->assign("calendarMessages", $this->formMessage->getSession());
		$this->formMessage->flush();
	}

}

?>
