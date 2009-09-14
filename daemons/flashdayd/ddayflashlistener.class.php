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


class DDayFlashListener extends Listener
{
	function eventOccured(Event $event)
	{
		$message = "'aujourd\'hui n\'oublie pas : '";
		$this->db->exec("INSERT INTO flashmail(`date`, from_user_id, to_user_id, message) 
    SELECT NOW(), 0, o.user_id, CONCAT(".$message.",d.event) 
	FROM (dday d, onlineusers o) 
	LEFT JOIN flashmail f ON (f.to_user_id = o.user_id AND f.message=CONCAT(".$message.",d.event) AND DATE(f.`date`) = CURRENT_DATE()) 
	WHERE f.id IS NULL AND d.`date` = CURRENT_DATE();");
	}
}
