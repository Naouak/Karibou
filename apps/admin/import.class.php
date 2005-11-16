<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * Import config page
 * 
 * @package applications
 */
class Import extends Model
{
	function build()
	{
		$qry = "SELECT * FROM admin_import";
		try
		{
			$stmt = $this->db->prepare($qry);
			$stmt->execute();
		}
		catch( PDOException $e )
		{
			Debug::display($qry);
			Debug::kill($e->getMessage());
		}
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$this->assign("rows", $rows);
		
		if (isset($_SESSION["adminCheck"]))
		{
			$this->assign("check", $_SESSION["adminCheck"]);
			unset($_SESSION["adminCheck"]);
		}
		
		if (isset($this->args["editid"]) && $this->args["editid"] != "")
		{
			$this->assign ("editid", $this->args["editid"]);
		}
	}

}
?>
