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
		$id = $this->args['id'];
		
		$sql1 = "SELECT * FROM bugs WHERE Id=:id ORDER BY Id DESC";
		try {
			$stmt = $this->db->prepare($sql1);
			$stmt->bindValue(":id", $id, PDO::PARAM_INT);
			$stmt->execute();
		} catch (PDOException $e)
		{
			Debug::kill($e->getMessage());
		}

		$bug = $stmt->fetchAll();
		
		
		$sql2 = "SELECT * FROM modules ORDER BY id DESC";
		try {
			$req = $this->db->prepare($sql2);
			$req->execute();
		} catch (PDOException $e)
		{
			Debug::kill($e->getMessage());
		}

		$modules = $req->fetchAll();
		$module = $modules[$bug[0]["module_id"]];
		
		$this->assign("modules",$modules);
		$this->assign("current_module",$module[0]);
		$this->assign("bug",$bug[0]);
		$this->assign("currentuser",$this->currentUser->getID());
		$this->assign("isadmin", $this->getPermission() == _ADMIN_);
		
	}
}
 
