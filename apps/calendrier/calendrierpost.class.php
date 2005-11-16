<?php 

/**
 * @version $Id: minichatgrand.class.php,v 1.2 2004/12/03 00:52:56 jon Exp $
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MiniChatPost extends Model
{
	public function build()
	{
		$req_sql = "INSERT INTO minichat VALUES (NOW(), " . 
		 	$this->currentUser->getID() . ", '" . 
		 	$_POST['post'] . "')";

		$this->db->exec($req_sql);
	}
}