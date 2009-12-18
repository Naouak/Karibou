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
		//Sélection des modules à afficher dans le template.
		$sql = "SELECT * from bugs_module ORDER BY id ASC";
		try {
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}
		/*$browser = new Browscap(KARIBOU_CACHE_DIR);
		$brow = $browser->getBrowser(null, true);
		echo("<br /><br /><br /><br /><br /><br />");
		print_r($brow);

		if($browser->getBrowser(null, true) != false) {
		echo("<br /><br /><br /><br /><br /><br />");

			print_r($browser);
			$brow = $browser["platform"] . " " . $browser["browser"] . " " . $browser["version"];
		} else {
			$brow = "inconnu";
		echo("<br /><br /><br /><br /><br /><br />");
		print_r($brow);
		//print_r($browser);
		var_dump($browser);
		}*/
		$modules = $stmt->fetchAll();

		//$this->assign("browser",$brow);
		$this->assign("modules", $modules);
	}
}
?>