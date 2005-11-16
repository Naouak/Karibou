<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information (GPL).
 * 
 * @package daemons
 **/

/**
 * @package daemons
 */
class VisitorsLogout extends Listener
{
	function eventOccured(Event $event)
	{
		$currentUser = $this->userFactory->getCurrentUser();

		$sql = "DELETE FROM onlineusers";
		$sql .= " WHERE user_id='".$currentUser->getID()."'";

		try
		{
			$this->db->exec($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill( $e->getMessage() );
		}
	}
}

?>
