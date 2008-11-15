<?php
/**
 * @copyright 2008 Pierre Quetelart <pquetelart@elv.telecom-lille1.eu>
 * @copyright 2008 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * Modify a group
 * 
 * @package applications
 */
class ModifyGroup extends FormModel
{
	function getPermission()
	{
		$groupid = filter_input(INPUT_POST,"groupid",FILTER_VALIDATE_INT);
		if ($groupid == false)
			Debug::kill("Error : groupid is invalid");
		
		// Check rights
		$framework = $GLOBALS['config']['bdd']["frameworkdb"];
		$stmt = $this->db->prepare("SELECT role FROM " . $framework . ".group_user WHERE group_id=:group AND user_id=:user");
		$stmt->bindValue(":group", $groupid);
		$stmt->bindValue(":user", $this->currentUser->getID());
		$stmt->execute();
		if ($row = $stmt->fetch())
			if ($row[0] == 'admin')
				return _FULL_WRITE_;
		return _NO_ACCESS_;
	}
	
	function build()
	{
		$framework = $GLOBALS['config']['bdd']["frameworkdb"];
		
		if (isset($_POST['changedescription']))
		{
			$cdescription = filter_input(INPUT_POST,"changedescription",FILTER_SANITIZE_SPECIAL_CHARS);
		}
		if (isset($_POST['description']))
		{
			$description = filter_input(INPUT_POST,"description",FILTER_SANITIZE_SPECIAL_CHARS);
		}
		$groupid = filter_input(INPUT_POST,"groupid",FILTER_VALIDATE_INT);
		if ($groupid == false)
			Debug::kill("Error : groupid is invalid");
		
		$removed = array();
		$memberPromotion = array();
		$adminPromotion = array();
		foreach ($_POST as $key => $value) {
			if (preg_match('/^option_(\d+)$/i', $key, $result)) {
				if ($value == "remove")
					$removed[] = $result[1];
				else if ($value == "member")
					$memberPromotion[] = $result[1];
				else if ($value == "admin")
					$adminPromotion[] = $result[1];
			}
		}
		
		if (count($removed) > 0) {
			$query = "DELETE FROM ".$framework.".group_user WHERE user_id IN (" . implode(",", $removed) . ") AND group_id=:groupid";
			$stmt = $this->db->prepare($query);
			$stmt->bindValue(":groupid", $groupid);
			$stmt->execute();
		}
		
		if (count($memberPromotion) > 0) {
			$query = "UPDATE ".$framework.".group_user SET role='member' WHERE user_id IN (" . implode(",", $memberPromotion) . ") AND group_id=:groupid";
			$stmt = $this->db->prepare($query);
			$stmt->bindValue(":groupid", $groupid);
			$stmt->execute();
		}
		
		if (count($adminPromotion) > 0) {
			$query = "UPDATE ".$framework.".group_user SET role='admin' WHERE user_id IN (" . implode(",", $adminPromotion) . ") AND group_id=:groupid";
			$stmt = $this->db->prepare($query);
			$stmt->bindValue(":groupid", $groupid);
			$stmt->execute();
		}
		
		if ((isset($cdescription)) && $cdescription !=$description)
		{
		//requete SQL pour changer description

			try
			{
				$stmt=$this->db->prepare("UPDATE ".$framework.".groups
								SET description=:cdescription
								WHERE id=:groupid
								LIMIT 1");
				$stmt->bindValue(':cdescription', $cdescription);
				$stmt->bindParam(':groupid', $groupid, PDO::PARAM_INT);
				$stmt->execute();
			}
			catch ( PDOException $e )
			{
				Debug::kill($e->getMessage());
			}
		}
	}
}

?>
