<?php

/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information (GPL).
 *-
 * @package daemons
 **/

/**
 * @package daemons
 */


class DDayFlashListener extends Listener
{
	function eventOccured(Event $event)
	{
		$sql = "SELECT event from dday where `date` = CURRENT_DATE";
		try
		{
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		while ( ($fetch=$stmt->fetch(PDO::FETCH_ASSOC)) !== false)
		{
			$message = "Aujourd'hui, n'oublie pas : " . $fetch["event"];
			$user_id = $this->currentUser->getID();
			$sql1 = "SELECT id from flashmail where date(`date`)=CURRENT_DATE and message = :message and to_user_id = :user_id";
			try {
				$stmt1 = $this->db->prepare($sql1);
				$stmt1->bindValue(":message", $message);
				$stmt1->bindValue(":user_id", $user_id);
				$stmt1->execute();
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
			$fetch1=$stmt1->fetch(PDO::FETCH_ASSOC);
			if ($fetch1["id"]==false)
			{
				$sql2="INSERT into flashmail (`date`,from_user_id,to_user_id,message) VALUES (NOW(), :fromid, :toid,:message)";
				try
				{
					$stmt2 = $this->db->prepare($sql2);
					$stmt2->bindValue(":fromid", "0");
					$stmt2->bindValue(":toid", $user_id);
					$stmt2->bindValue(":message", $message);
					$stmt2->execute();
				}
				catch(PDOException $e)
				{
					Debug::kill($e->getMessage());
				}
			}
		}
	}
}
