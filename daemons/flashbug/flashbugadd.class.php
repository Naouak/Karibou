<?php

/**
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information (GPL).
 *-
 * @package daemons
 **/

/**
 * @package daemons
 */


class FlashBugAdd extends Listener
{
	function eventOccured(Event $event)
	{
		$message = "'Un nouveau bug vous a été assigné : '";
		$this->db->exec(
			"INSERT INTO
				flashmail(`date`, from_user_id, to_user_id, message)
			SELECT
				NOW(), 1, ba.user_id, CONCAT(".$message.",bb.summary)
			FROM
				(bugs_bugs bb, bugs_assign ba)
			LEFT JOIN
				flashmail f ON (f.to_user_id = ba.user_id AND f.message=CONCAT(".$message.",bb.summary) AND DATE(f.`date`) = CURRENT_DATE() AND f.from_user_id=1)
			WHERE
				f.id IS NULL AND bb.id = (SELECT MAX(id) FROM bugs_bugs) AND ba.bugs_id = bb.id;"
		);

		/*try {
			$this->db->execute();
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		} */
	}
}
 
