<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 * @copyright 2009 Rémy Sanchez <remy.sanchez@hyperthese.net>
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
		$delete = $this->db->prepare("DELETE FROM onlineusers WHERE timestamp < :time");
		$delete->bindValue(":time", (time() - $this->maxAge));

		try {
			$delete->execute();
		} catch(PDOException $ex) {
			trigger_error("Unable to prune onlineusers ($ex)", E_USER_WARNING);
		}

		if ($currentUser->getID() != 0)
		{
			$insert = $this->db->prepare("
				INSERT INTO
				onlineusers (user_id, timestamp, user_ip, proxy_ip)
				VALUES
				(:user_id, :time, INET_ATON(:user_ip), INET_ATON(:proxy_ip))
				ON DUPLICATE KEY UPDATE
				timestamp = :time
				");
			if (strpos(":", $_SERVER["REMOTE_ADDR"]) === FALSE) {
				$insert->bindValue(":user_ip", (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : 
					$_SERVER["REMOTE_ADDR"]));
				$insert->bindValue(":proxy_ip", (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["REMOTE_ADDR"] : null), 
				(isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? PDO::PARAM_INT : PDO::PARAM_NULL));
			} else {
				$insert->bindValue(":user_ip", "0.0.0.0");
				$insert->bindValue(":proxy_ip", PDO::PARAM_NULL);
			}
			$insert->bindValue(":time", time());
			$insert->bindValue(":user_id", $currentUser->getID());
			try {
				$insert->execute();
			} catch(PDOException $ex) {
				trigger_error("Unable to update onlineusers ($ex)", E_USER_WARNING);
			}
		}
	}
}
