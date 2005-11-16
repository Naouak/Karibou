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
class VisitorsLogin extends Listener
{
	function eventOccured(Event $event)
	{
		$currentUser = $this->userFactory->getCurrentUser();

		if ( $currentUser->getID() == 0 ) return;
		
		$sql = "INSERT INTO onlineusers (user_id, timestamp) VALUES (".$currentUser->getID().", ".time().") ";
		$sql .= " ON DUPLICATE KEY UPDATE timestamp=".time()." ; ";
		try
		{
			$this->db->exec($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
	}
}

?>
