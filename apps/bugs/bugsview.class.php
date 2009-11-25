<?php

/**
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/

class BugsView extends Model
{
	public function build()
	{
		$id = $this->args['id'];

		$sql = $this->db->prepare("SELECT * FROM bugs_bugs WHERE id=:id ORDER BY id DESC LIMIT 1");
		$stmt = $this->db->prepare("SELECT * FROM bugs_assign WHERE bugs_id=:bugs_id");
		$req = $this->db->prepare("SELECT * FROM bugs_module WHERE id=:id");
		
		try {
			$sql->bindValue(":id",$id, PDO::PARAM_INT);
			$sql->execute();
			$bug = $sql->fetchAll();

			$req->bindValue(":id",$bug[0]['module_id']);
			$req->execute();
			$module = $req->fetchAll();

			$stmt->bindValue(":bugs_id",$bug[0]['id'], PDO::PARAM_INT);
			$stmt->execute();
			$assign = $stmt->fetchAll();

			$dev = 0;
			foreach($assign as $value)
			{
				if($value['user_id'] == $this->currentUser->getID())
					$dev = 1;
			}
			
			
			$name=$this->appname."-".$bug[0]['id'];
			$combox = new CommentSource($this->db,$name,"",$bug[0]["bug"]);
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}

		if ($user["object"] =  $this->userFactory->prepareUserFromId($bug[0]["reporter_id"]))
		{
			$this->assign("module", $module[0]);
			$this->assign("dev",$dev);
			$this->assign("bug_author", $user);
			$this->assign("bug",$bug[0]);
			$this->assign("currentuser",$this->currentUser->getId());
			$this->assign("idcombox",$combox->getId());
			$this->assign("isadmin",$this->getPermission() == _ADMIN_);
		}

		
	}
}