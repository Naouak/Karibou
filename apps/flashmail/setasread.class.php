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
class FlashMailSetAsRead extends Model
{
	function build()
	{
		if (isset($this->args["flashmailid"]))
		{
					$uqry = "
							UPDATE flashmail
							SET `read` = 1
							WHERE
                            to_user_id = ".$this->currentUser->getId()."
                            AND
                            id = ".$this->args["flashmailid"];
					try
					{
                   		$this->db->exec($uqry);
					}
					catch(PDOException $e)
					{
						Debug::kill($e->getMessage() );
					}
		}
	}
}

?>
