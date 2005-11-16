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


class CalendarSave extends FormModel
{
	public function build()
	{
		if (isset($_POST))
		{

			if (
				isset(
					$_POST["name"],
					$_POST["type"]
					))
			{
			
				//TODO: VERIFIER LES DROITS!!!
				
				if (isset($_POST["calendarid"]) && $_POST["calendarid"] != '')
				{
					$calendarid = $_POST["calendarid"];
					//UPDATE
					$sql = "UPDATE calendar 
							  SET	name = '".$_POST["name"]."', 
							  		type = '".$_POST["type"]."'
							  WHERE id = '".$_POST["calendarid"]."'";
			
					try
					{
						$this->db->exec($sql);						
					}
					catch(PDOException $e)
					{
						Debug::kill($e->getMessage());
					}
					$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("CALENDARMODIFIED"));
				}
				else
				{
					//INSERT
					$sql = "INSERT INTO calendar 
							  (name, type)
							  VALUES ('".$_POST["name"]."','".$_POST["type"]."') ; ";
					if( $_POST['destination'] == 0 )
					{
						$sql .= "INSERT INTO calendar_user 
								  (user_id, calendar_id)
								  VALUES ('".$this->currentUser->getID()."', LAST_INSERT_ID() ) ; ";
					}
					else
					{
						$sql .= "INSERT INTO calendar_group 
								  (group_id, calendar_id)
								  VALUES ('".$_POST['destination']."', LAST_INSERT_ID() ) ; ";
					}
			
					try
					{
						$this->db->exec($sql);
					}
					catch(PDOException $e)
					{
						Debug::kill($e->getMessage());
					}
					$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("CALENDARADDED"));
				}
			
				$this->setRedirectArg('app', 'calendar');
				$this->setRedirectArg('page', 'manage');

				$this->formMessage->setSession();
			}
		}
	}

}

?>