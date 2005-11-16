<?php
/**
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
class FlashMailHeaderBox extends Model
{
	function build()
	{
		$flashmailFactory = new FlashMailFactory($this->db, $this->currentUser, $this->userFactory);

		$this->assign("flashmails", $flashmailFactory->returnUnread());

		//$flashmailFactory->setAsRead();
	}
}

?>
