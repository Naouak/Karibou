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
class VisitorsLoad extends Listener
{
	protected $maxAge = 600;
	
	function eventOccured(Event $event)
	{
		$currentUser = $this->userFactory->getCurrentUser();

		$sql_delete = "DELETE FROM onlineusers";
		$sql_delete .= " WHERE timestamp < ".(time()-$this->maxAge)." ; ";
		if ( $currentUser->getID() != 0 )
		{
			$sql_insert = "INSERT INTO onlineusers (user_id, timestamp) VALUES (".$currentUser->getID().", ".time().") ";
			$sql_insert .= " ON DUPLICATE KEY UPDATE timestamp=".time()." ; ";
		}
		try
		{
			$this->db->exec($sql_delete);
			if (isset($sql_insert))
			{
				$this->db->exec($sql_insert);
			}
		}
		catch(PDOException $e)
		{
			Debug::kill( $e->getMessage() );
		}
	}
}

?>
