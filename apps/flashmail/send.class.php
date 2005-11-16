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
					INSERT INTO flashmail
					(from_user_id, to_user_id, message, date, omsgid)
					VALUES
					('".$this->currentUser->getID()."', '".$_POST["to_user_id"]."', '".$_POST["message"]."', NOW(),'".$_POST["omsgid"]."')";
				
				try
				{
					$this->db->exec($qry);
					if (isset($_POST["omsgid"]) && $_POST["omsgid"] != "")
					{
						$uqry = "
							UPDATE flashmail
							SET `read` = 1
							WHERE to_user_id = ".$this->currentUser->getId()."
							AND id = ".$_POST["omsgid"];

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
				catch(PDOException $e)
				{
					Debug::kill($e->getMessage() );
				}
			}
			else
			{
				Debug::kill($_POST);
			}
			
		}
		$this->setRedirectArg('app', 'flashmail');
		$this->setRedirectArg('page', 'headerbox');
	}
}

?>