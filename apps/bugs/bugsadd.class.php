<?php

/**
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/

class BugsAdd extends Model
{

	public function build() {
		//$this->currentuser = $this->userFactory->getCurrentUser();
		//$app = $this->appList->getApp($this->appname);
		//$app->addView("menu", "header_menu", array("page" => "add"));
		$sql = "SELECT * from modules ORDER BY id ASC";
		try {
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}

		$modules = $stmt->fetchAll();
		
		$this->assign("modules", $modules);
		$this->assign("message",$this->formMessage->getSession());
	}
}
?>