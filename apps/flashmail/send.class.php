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
class FlashMailSend extends FormModel
{
	function build()
	{
		if (isset($_POST))
		{
			if (isset($_POST["to_user_id"], $_POST["message"]))
			{
				$qry = "
					INSERT INTO flashmail (from_user_id, to_user_id, message, date, omsgid)
					VALUES (:userId, :toUserId, :message, NOW(), :omsgid)";
				try
				{
					$stmt = $this->db->prepare($qry);
					$stmt->bindValue(":userId", $this->currentUser->getId());
					$stmt->bindValue(":toUserId", filter_input(INPUT_POST, "to_user_id", FILTER_VALIDATE_INT));
					$stmt->bindValue(":message", filter_input(INPUT_POST, "message"));
					$stmt->bindValue(":omsgid", filter_input(INPUT_POST, "omsgid", FILTER_VALIDATE_INT));
					$stmt->execute();
					if (isset($_POST["omsgid"]) && $_POST["omsgid"] != "")
					{
						$qry = "UPDATE flashmail SET `read` = 1 WHERE to_user_id=:toUserId AND id=:id";
						$stmt = $this->db->prepare($qry);
						$stmt->bindValue(":toUserId", $this->currentUser->getId());
						$stmt->bindValue(":id", filter_input(INPUT_POST, "omsgid", FILTER_VALIDATE_INT));
						$stmt->execute();
					}
				}
				catch(PDOException $e)
				{
					print_r($e);
					Debug::kill($e->getMessage() );
				}
				KacheControl::instance()->renew("flashmail");
			}
			else
			{
				Debug::kill($_POST);
			}
			
		}
	}
}

?>
