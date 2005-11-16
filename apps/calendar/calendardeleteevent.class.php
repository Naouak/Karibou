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


class CalendarDeleteEvent extends FormModel
{
	public function build()
	{
 		if (isset($_POST['calendarid'],$_POST['eventid']) && $_POST['calendarid'] != '' && $_POST['eventid'] != '')
		{
			//Mode edition
			$sql = "
				SELECT * FROM calendar_event WHERE calendar_id = ".$_POST['calendarid']." AND id = '".$_POST['eventid']."'
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
if ($event->authorid == $this->currentUser->getId())
{
$myCalendarWriter = new CalendarWriterDB($this->db, $_POST["calendarid"]);
if ($myCalendarWriter->deleteEvent($event))
{
    $this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("EVENTDELETED"));
}
else
{
    $this->formMessage->add (FormMessage::WARNING, $this->languageManager->getTranslation("EVENTNOTDELETED"));
}
}
			}
			else
			{
			}

		}
		else
		{
            Debug::kill("No such event");
		}
	}

}

?>
