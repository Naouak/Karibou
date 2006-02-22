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
class KFMessageSave extends FormModel
{
	public function build()
	{
		$forumid = 1;
		$factory = new KFFactory ($this->db, $this->userFactory);
	
		if (isset($_POST["forumid"]))
		{
			if (isset($_POST["messageid"]))
			{
				//Update
				$myMessage = $factory->getMessageById($_POST["forumid"], $_POST["messageid"]);
				if ($myMessage->canUpdate())
				{
					$myMessage->setInfosFromArray($_POST["messageinfos"]);
					$factory->saveElement($myMessage);
				}
			}
			elseif (isset($_POST))
			{
				//Add
				$myMessage = $factory->createMessage($_POST["forumid"]);
				$myMessage->setInfosFromArray($_POST["messageinfos"]);
				$elementid = $factory->saveElement($myMessage);
			}			
		}
		$this->setRedirectArg('page', 'messageedit');
		$this->setRedirectArg('forumid', 1);
	}
}

?>