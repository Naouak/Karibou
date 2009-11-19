<?php

/**
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/

class BugsModify extends Model
{
	function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$devGroupId = $config["developersGroup"]["id"];
		
		$id = $this->args['id'];
		
		$sql1 = $this->db->prepare("SELECT * FROM bugs_bugs WHERE id=:id ORDER BY Id DESC");
		$sql1->bindValue(":id", $id, PDO::PARAM_INT);
		
		$sql2 = $this->db->prepare("SELECT * FROM bugs_module ORDER BY id ASC");
		$stmt = $this->db->prepare("SELECT * FROM bugs_assign WHERE bugs_id=:bugs_id");
		
		try {
			
			$sql1->execute();
			$sql2->execute();
			
			$bug = $sql1->fetchAll();
			$modules = $sql2->fetchAll();

			$stmt->bindValue(":bugs_id",$bug[0]['id'], PDO::PARAM_INT);
			$stmt->execute();
			$assign = $stmt->fetchAll();

			$dev = 0;
			foreach($assign as $value)
			{
				if($value['user_id'] == $this->currentUser->getID())
					$dev = 1;
			}
			$devlist = $this->userFactory->getUsersFromGroup($this->userFactory->getGroupsFromId($devGroupId));
			
		} catch (PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
	
		$module = $modules[$bug[0]["module_id"]];

		$this->assign("devlist", $devlist);
		$this->assign("dev",$dev);
		$this->assign("modules",$modules);
		$this->assign("current_module",$module[0]);
		$this->assign("bug",$bug[0]);
		$this->assign("currentuser",$this->currentUser->getID());
		$this->assign("isadmin", $this->getPermission() == _ADMIN_);
		
	}
}
 