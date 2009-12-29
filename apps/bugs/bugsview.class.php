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
		//Le insert est utilisé pour l'incription.
		$insert = filter_input(INPUT_POST, "insert", FILTER_SANITIZE_NUMBER_INT);
		
		$id = $this->args['id'];

		$sql4 = $this->db->prepare("DELETE FROM bugs_subscribe WHERE user_id=:user_id AND bugs_id=:bugs_id");
		$sql3 = $this->db->prepare("SELECT * FROM bugs_subscribe WHERE user_id=:user_id AND bugs_id=:bugs_id");
		$sql2 = $this->db->prepare("INSERT IGNORE INTO bugs_subscribe (user_id, bugs_id) VALUES (:user_id, :bugs_id)");
		$sql = $this->db->prepare("SELECT * FROM bugs_bugs WHERE id=:id ORDER BY id DESC LIMIT 1");
		$stmt = $this->db->prepare("SELECT * FROM bugs_assign WHERE bugs_id=:bugs_id");
		$req = $this->db->prepare("SELECT * FROM bugs_module WHERE id=:id");
		
		try {
			//Si le type s'inscrit, on le met dans subscribe, s'il se désinscrit, on le vire, sinon on fait rien.
			if($insert == 1) {
				$sql2->bindValue(":user_id",$this->currentUser->getID());
				$sql2->bindValue(":bugs_id", $id);
				$sql2->execute();
			} elseif ($insert == 2) {
				$sql4->bindValue(":user_id",$this->currentUser->getID());
				$sql4->bindValue(":bugs_id", $id);
				$sql4->execute();
			}

			//On sélectionne regarde si l'user est inscrit. Cela conditionnera le bouton s'inscrire/se désinscrire.
			$sql3->bindValue(":user_id",$this->currentUser->getID());
			$sql3->bindValue(":bugs_id",$id);
			$sql3->execute();
			$fetch = $sql3->fetch();

			$subs = 0;
	
			if($fetch !=null) {
				$subs = 1;
			}
			
			$sql->bindValue(":id",$id);
			$sql->execute();
			$bug = $sql->fetch();

			$req->bindValue(":id",$bug['module_id']);
			$req->execute();
			$module = $req->fetch();

			$stmt->bindValue(":bugs_id",$bug['id']);
			$stmt->execute();
			$assign = $stmt->fetchAll();

			$dev = 0;
			foreach($assign as $value)
			{
				if($value['user_id'] == $this->currentUser->getID())
					$dev = 1;
			}
			
			
			$name=$this->appname."-".$bug['id'];
			$combox = new CommentSource($this->db,$name,"",$bug["bug"]);
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}

		if ($user["object"] =  $this->userFactory->prepareUserFromId($bug["reporter_id"]))
		{
			$this->assign("subs",$subs);
			$this->assign("module", $module);
			$this->assign("dev",$dev);
			$this->assign("bug_author", $user);
			$this->assign("bug",$bug);
			$this->assign("currentuser",$this->currentUser->getID());
			$this->assign("idcombox",$combox->getId());
			$this->assign("isadmin",$this->getPermission() == _ADMIN_);
		}

		
	}
}