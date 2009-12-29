<?php

/**
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/

class BugsModifyMod extends Model
{
	function build()
	{
		//Configuration pour obtenir les développeurs
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$devGroupId = $config["developersGroup"]["id"];

		//Récupération de l'id du module.
		$id = $this->args['id'];

		$sql = $this->db->prepare("SELECT * FROM bugs_module WHERE id=:id");
		$sql->bindValue("id",$id);
		$stmt = $this->db->prepare("SELECT user_id FROM bugs_dev WHERE module_id=:id");
		$stmt->bindValue("id",$id);

		try {
			$sql->execute();
			$module = $sql->fetch();

			$stmt->execute();
			$devs = $stmt->fetchAll();

			$developers = array();
			foreach($devs as $value) {
				$dev = $value["user_id"];
				$developers[] = $dev;
			}


			$this->assign("devlist", $this->userFactory->getUsersFromGroup($this->userFactory->getGroupsFromId($devGroupId)));
			$this->assign("module",$module);
			$this->assign("devs",$developers);
			
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}
	}
}
