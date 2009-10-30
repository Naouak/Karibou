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
		$sql = "SELECT *, 
			DATE_FORMAT(`date`, \"%Y%m%d\") AS vcalDateStart,
			DATE_FORMAT(DATE_ADD(`date`, INTERVAL 1 DAY), \"%Y%m%d\") AS vcalDateEnd,
			(TO_DAYS(`date`) - TO_DAYS(CURRENT_DATE())) AS JJ 
			FROM `dday` WHERE date >= CURRENT_DATE() AND visible = '1' ORDER BY date LIMIT 5";
		try
		{
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
