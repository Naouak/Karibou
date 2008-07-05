<?php 
/**
 * @copyright 2008 Mick, inspired by Simon Lehembre
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

class BigDayTof extends Model
{
	public function build()
	{
		try
		{
			$stmt = $this->db->prepare("SELECT * FROM daytof WHERE 1 ORDER BY datetime DESC LIMIT 10");
			$stmt->execute();	
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}

		if ($photos = $stmt->fetchall(PDO::FETCH_ASSOC)) {
			foreach ($photos as $id => $items) {
				$photos[$id]["user"] = $this->userFactory->prepareUserFromId($photos[$id]["user_id"]);
				$photos[$id]["photo"] = "PIC" . str_pad($photos[$id]["id"], 5, "0", STR_PAD_LEFT);
			}
			$this->assign("tofarray", $photos);
		}
	}
}

?>
