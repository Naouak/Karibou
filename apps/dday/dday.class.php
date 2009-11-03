<?php 
/**
 * @copyright 2007 Charles Anssens
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/** 
 * 
 * @package applications
 **/
class Dday extends Model
{
	public function build()
	{
		try
		{
			$sql = "SELECT *, (days - TO_DAYS(CURRENT_DATE())) AS JJ 
				FROM `dday` WHERE (days >= TO_DAYS(CURRENT_DATE())) AND visible = 1 ORDER BY JJ DESC LIMIT 5";
			$stmt = $this->db->query($sql);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}

		if($ddaylist = $stmt->fetchAll(PDO::FETCH_ASSOC))
		{
			$this->assign("ddaylist",$ddaylist);
		}
		else
		{
			$this->assign("DDempty","Err empty");
		}
		$this->assign("isadmin",$this->getPermission() == _ADMIN_);
		$this->assign("currentuser", $this->currentUser->getId());
	}
}

?>
