<?php

/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 * @copyright 2009 Pierre DUCROQUET <pinaraf@pinaraf.info>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information (GPL).
 *-
 * @package daemons
 **/

/**
 * @package daemons
 */


class PollFlashListener extends Listener
{
	function eventOccured(Event $event)
	{
		$message = "'n\'oublie pas de voter pour ce nouveau sondage : '"; 
		$this->db->exec("INSERT INTO flashmail (`date`, from_user_id, to_user_id, message) 
		SELECT NOW(), 1, o.user_id, CONCAT(".$message.",p.question) FROM (onlineusers AS o , polls AS p)
		LEFT JOIN flashmail AS f ON (f.to_user_id=o.user_id AND f.message = CONCAT(".$message.",p.question)) 
		LEFT JOIN poll_votes AS v ON v.user=o.user_id AND v.poll=p.id 
		WHERE f.message IS NULL AND p.id=(SELECT MAX(id) FROM polls) AND v.id IS NULL AND f.from_user_id=1;");
	}
}
