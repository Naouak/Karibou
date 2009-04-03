<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2009 Pierre Ducroquet <pinaraf@gmail.com>
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

		$qry = $this->db->prepare("DELETE FROM onlineusers WHERE user_id=:userId");
		$qry->bindValue(":userId", $currentUser->getID());

		try
		{
			$qry->execute();
		}
		catch(PDOException $e)
		{
			Debug::kill( $e->getMessage() );
		}
	}
}

?>
