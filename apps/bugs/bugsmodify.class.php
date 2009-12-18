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
		//Configuration pour obtenir les développeurs
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$devGroupId = $config["developersGroup"]["id"];

		//Récupération de l'id du bug.
		$id = $this->args['id'];

		//Variable conditionnant l'affichage de la page de modification du bug (si le bug existe ou non).
		$display = 1;

		//Recherche de doublons du bug
		$search = filter_input(INPUT_POST, "search", FILTER_SANITIZE_SPECIAL_CHARS);

		//Sélection du bug à modifier
		$sql1 = $this->db->prepare("SELECT * FROM bugs_bugs WHERE id=:id");
		$sql1->bindValue(":id", $id);

		//Requêtes pour l'assignation du bug à des développeurs
		$sql2 = $this->db->prepare("SELECT * FROM bugs_module ORDER BY id ASC");
		$stmt = $this->db->prepare("SELECT * FROM bugs_assign WHERE bugs_id=:bugs_id");

		//Recherche de doublons
		if($search != null) {
				
			$sql = $this->db->prepare("SELECT id,summary FROM bugs_bugs WHERE summary LIKE :search OR bug LIKE :search");
			$sql->bindValue("search",'%'.$search.'%');
			try {
				$sql->execute();
				$content = $sql->fetchAll();
			} catch (PDOException $e) {
				Debug::kill($e->getMessage());
			}

		}
		
		try {
			
			$sql1->execute();
			$sql2->execute();
			
			$bug = $sql1->fetch();
			$modules = $sql2->fetchAll();

			//On affiche la page d'erreur si le bug n'existe pas
			if($bug["bug"] == null)
				$display = 0;

			$stmt->bindValue(":bugs_id",$bug['id']);
			$stmt->execute();
			$assign = $stmt->fetchAll();

			//On détecte si l'user fait partie des développeurs déjà assignés
			$dev = 0;
			if(in_array($this->currentUser->getID(), $assign));
				$dev = 1;
			$devlist = $this->userFactory->getUsersFromGroup($this->userFactory->getGroupsFromId($devGroupId));
			
		} catch (PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
	
		$module = $modules[$bug["module_id"]];

		$this->assign("display", $display);
		$this->assign("devlist", $devlist);
		$this->assign("dev",$dev);
		$this->assign("modules",$modules);
		$this->assign("current_module",$module[0]);
		$this->assign("bug",$bug);
		$this->assign("currentuser",$this->currentUser->getID());
		$this->assign("isadmin", $this->getPermission() == _ADMIN_);
		$this->assign("search", $content);
		
	}
}
 
