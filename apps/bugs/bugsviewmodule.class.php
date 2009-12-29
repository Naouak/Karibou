<?php

/**
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/

class BugsViewModule extends Model
{
	public function build()
	{
		$id = $this->args['id'];
		$sql = $this->db->prepare("SELECT * FROM bugs_module WHERE id=:id");
		$sql->bindValue("id",$id);
		$stmt = $this->db->prepare("SELECT * FROM bugs_dev WHERE module_id=:id");
		$stmt->bindValue("id",$id);

		try{
			$sql->execute();
			$module = $sql->fetch();

			$stmt->execute();

			$devs = array();
			while($devsRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
				if ($user["object"] =  $this->userFactory->prepareUserFromId($devsRow["user_id"])) {
					$dev["user_id"] = $devsRow["user_id"];
					$dev["module_id"] = $devsRow["module_id"];
					$dev["user"] = $user["object"];
					$devs[]=$dev;
				}
			}
			$this->assign("isadmin",$this->getPermission() == _ADMIN_);
			$this->assign("module",$module);
			$this->assign("devs",$devs);
		} catch (PDOException $e) {
			$e->getMessage();
		}
	}
}
