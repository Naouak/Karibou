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
class AddUserToGroup extends FormModel
{
	function getPermission()
	{
		$groupid = filter_input(INPUT_POST,"groupid",FILTER_VALIDATE_INT);
		if ($groupid == false)
			Debug::kill("Error : groupid is invalid");
		$userid = filter_input(INPUT_POST,"userid",FILTER_VALIDATE_INT);
		if ($userid == false)
			Debug::kill("Error : userid is invalid");
		// Check rights
		$framework = $GLOBALS['config']['bdd']["frameworkdb"];
		$stmt = $this->db->prepare("SELECT role FROM " . $framework . ".group_user WHERE group_id=:group AND user_id=:user");
		$stmt->bindValue(":group", $groupid);
		$stmt->bindValue(":user", $userid);
		$stmt->execute();
		if ($row = $stmt->fetch())
			if ($row[0] == 'admin')
				return _FULL_WRITE_;
		return _NO_ACCESS_;
	}
	
	function build()
	{
		$groupid = filter_input(INPUT_POST,"group_id",FILTER_VALIDATE_INT);
		if ($groupid == false)
			Debug::kill("Error : groupid is invalid");
		$userid = filter_input(INPUT_POST,"user_id",FILTER_VALIDATE_INT);
		if ($userid == false)
			Debug::kill("Error : userid is invalid");
		
		$framework = $GLOBALS['config']['bdd']["frameworkdb"];
		
		try {
			$stmt = $this->db->prepare("INSERT INTO " . $framework . ".group_user(user_id, group_id) VALUES(:user, :group)");
			$stmt->bindValue(":user", $userid);
			$stmt->bindValue(":group", $groupid);
			$stmt->execute();
			$this->setRedirectArg("id", $groupid);
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}
	}
}

?>