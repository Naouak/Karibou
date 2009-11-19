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
		$sql = "SELECT * from bugs_module ORDER BY id ASC";
		try {
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}
		$browser = new Browscap(KARIBOU_CACHE_DIR);
		
		if($browser->getBrowser(null, true) != false)
			$brow = $browser["platform"] . " " . $browser["browser"] . " " . $browser["version"];
		else
			$brow = "inconnu";
		echo("<br /><br /><br /><br /><br /><br />");
		print_r($brow);
		//print_r($browser);
		var_dump($browser);
		$modules = $stmt->fetchAll();

		$this->assign("browser",$brow);
		$this->assign("modules", $modules);
		$this->assign("message",$this->formMessage->getSession());
	}
}
?>