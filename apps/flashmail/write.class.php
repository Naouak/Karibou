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
class FlashMailWrite extends Model
{
	function build()
	{
	if (isset($this->args["flashmailid"]) && $this->args["flashmailid"] != '')
	{
		$qry = "SELECT * FROM flashmail
		WHERE to_user_id=".$this->currentUser->getID()."
		AND id=".$this->args["flashmailid"]."";

		try
		{
			$stmt = $this->db->query($qry);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage() );
		}

		$flashmails = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (count($flashmails) > 0)
		{
			$flashmail = new FlashMail ($this->userFactory, $flashmails[0]);
			$this->assign("flashmail", $flashmail);
		}
		unset($stmt);
	}
	elseif (isset($this->args["userid"]) && $this->args["userid"] != "")
	{
		$user = $this->userFactory->prepareUserFromId($this->args["userid"]);
		$this->assign("user", $user);
	}
	}
}

?>
