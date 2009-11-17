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

class BugsMini extends Model
{
	public function build()
	{
		$sql = "SELECT * FROM `bugs_bugs` ORDER BY id DESC LIMIT 30";
		try {
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$bugs = $stmt->fetchAll();		

		
			$i = 0; // $i représente le bug courant quand on parcourt la table
			$j = 10; //On n'affiche que 10 bugs. Incrémentation si le bug est un doublon pour ne pas afficher moins de 10 bugs.


			while ($i < $j) {
				if($bugs[$i]["doublon_id"] === null) {
					$bugsmini[$i] = $bugs[$i];
				} else {
					$j++;
				}
				$i++;
			}

			$this->assign("bugsmini", $bugsmini);
			$this->assign("bugs",$bugs);
			$this->assign("currentuser",$this->currentUser->getId());

		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}
		

	}
}
?>
