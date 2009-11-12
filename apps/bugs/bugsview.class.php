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

		$sql = "SELECT * FROM bugs WHERE Id=:id ORDER BY Id DESC LIMIT 1";
		
		try {
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":id",$id, PDO::PARAM_INT);
			$stmt->execute();
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}
		
		$bug = $stmt->fetchAll();
		$name=$this->appname."-".$bug[0]['Id'];
		$combox = new CommentSource($this->db,$name,"",$bug[0]["bug"]);
		
		if ($user["object"] =  $this->userFactory->prepareUserFromId($bug[0]["user_id"]))
		{
			//echo("<br /><br /><br />");
			//print_r($
			$this->assign("bug_author", $user);
			$this->assign("bug",$bug[0]);
			$this->assign("currentuser",$this->currentUser->getId());
			$this->assign("idcombox",$combox->getId());
			$this->assign("isadmin",$this->getPermission() == _ADMIN_);
		}

		
	}
}