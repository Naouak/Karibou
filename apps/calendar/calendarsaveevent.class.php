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


class CalendarSaveEvent extends FormModel
{
	public function build()
	{
		if (isset($_POST))
		{

			if (
				isset(
					$_POST["description"],
					$_POST["summary"],
					$_POST["startdateYear"],
					$_POST["startdateMonth"],
					$_POST["startdateDay"],
					$_POST["startdateHour"],
					$_POST["startdateMinute"],
					$_POST["enddateYear"],
					$_POST["enddateMonth"],
					$_POST["enddateDay"],
					$_POST["enddateHour"],
					$_POST["enddateMinute"],
					$_POST["location"],
					$_POST["category"],
					$_POST["priority"],
					$_POST["recurrence"]
					))
			{
			
				//TODO: VERIFIER LES DROITS!!!
			
				$myCalendarEvent = new CalendarEvent (
					0,
					$_POST["calendarid"],
					$this->currentUser->getId(),
					$_POST["description"],
					$_POST["summary"],
					$_POST["priority"],
					$_POST["startdateYear"]."-".$_POST["startdateMonth"]."-".$_POST["startdateDay"]." ".$_POST["startdateHour"].":".$_POST["startdateMinute"],
					$_POST["enddateYear"]."-".$_POST["enddateMonth"]."-".$_POST["enddateDay"]." ".$_POST["enddateHour"].":".$_POST["enddateMinute"],
					$_POST["location"],
					$_POST["category"],
					$_POST["recurrence"]
					);
				
				$myCalendarWriter = new CalendarWriterDB($this->db, $_POST["calendarid"]);
				if (isset($_POST["eventid"]) && $_POST["eventid"] != '')
				{
					$myCalendarEvent->uid = $_POST["eventid"];
					$myCalendarWriter->updateEvent($myCalendarEvent);
					$this->formMessage->add (FormMessage::SUCCESS, gettext("EVENTMODIFIED"));
				}
				else
				{
					$myCalendarWriter->addEvent($myCalendarEvent);
					$this->formMessage->add (FormMessage::SUCCESS, gettext("EVENTADDED"));
				}
			
				$this->setRedirectArg('app', 'calendar');
				$this->setRedirectArg('page', '');
				$this->setRedirectArg('year', $_POST["startdateYear"]);
				$this->setRedirectArg('month', $_POST["startdateMonth"]);
				$this->setRedirectArg('day', $_POST["startdateDay"]);
				
				$this->formMessage->setSession();
			}
		}
	}

}

?>
