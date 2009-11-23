<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 * @copyright 2009 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information (GPL).
 *
 * @package apps
 **/

/**
 * @package apps
 */
class FlashMailSetAsRead extends FormModel
{
	function build()
	{
		if (isset($_POST["flashmailid"]))
		{
			$qry = "UPDATE flashmail SET `read` = 1 WHERE to_user_id = :userId AND id=:flashmailId";
			try {
				$stmt = $this->db->prepare($qry);
				$stmt->bindValue(":userId", $this->currentUser->getId());
				$stmt->bindValue(":flashmailId", $_POST["flashmailid"]);
				$stmt->execute();
				KacheControl::instance()->renew("flashmail");
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage() );
			}
		}
	}
}

?>
