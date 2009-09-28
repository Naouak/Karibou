<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information (GPL).
 * 
 * @package apps
 **/

/**
 * @package apps
 */
class FlashMailList extends Model
{
	function build()
	{
		$flashmailFactory = new FlashMailFactory($this->db, $this->currentUser, $this->userFactory, false);
		$flashmailFactory->getAllWithSentFromDB();
		$all = $flashmailFactory->returnAll();
		$res = array();
		foreach ($all as $flashmail) {
			if ($flashmail->getOldMessageId() == 0)
				$res[] = $flashmail;
		}
		$this->assign("flashmails", $res);
	}
}

?>
