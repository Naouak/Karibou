<?php
/**
 * @copyright Grégoire Leroy  <lupuscramus@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/


/**
 *
 * @package applications
 */

// name of the class and extends permit to have an heritage of an class
class Bugs extends Model
{
// you would have the function build() in all apps you create
	public function build()
	{
		$app= $this->appList->getApp($this->Appname);
		$config= $app->getConfig();

		$sql = "SELECT * FROM `bugs` ORDER BY Id DESC LIMIT 30";
		try {
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}

		$bugs = array();
		while($bugRow = $stmt->fetch(PDO::FETCH_ASSOC)) {

				$bug = array();
				$bug["title"]=$bugRow["title"];
				$bug["state"]=$bugRow["bug_state"];
				$bug["bug"]=$bugRow["bug"];
				$bug["user_id"]=$bugRow["user_id"];
				$bug["id"]=$bugRow["Id"];
				$bug["module_id"]=$bugRow["module_id"];
				$bug["doublon"]=$bugRow["doublon"];
				$bugs[] = $bug;

		}

		$i = 0; // Bug affiché
		$j = 10; //On n'affiche que 10 bugs
		$k = 0; // Bug extrait de la base
		
		while ($i < $j) {
			if($bugs[$i]["doublon"] == 0) {
				/*echo("<br /><br /><br />");
				print_r($bugs[$i]["doublon"]);*/
				$bugsmini[$i] = $bugs[$k];
			} else {
				$j++;
			}
			$i++;
			$k++;
		}

		$this->assign("bugsmini", $bugsmini);
		$this->assign("bugs",$bugs);
		$this->assign("currentuser",$this->currentUser->getId());
		

	}
}
?>
