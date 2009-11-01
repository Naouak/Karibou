<?php
/**
 * @copyright Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/
 
 
/**
 * Classe rumour
 *
 * @package applications
 */

class rumour extends Model {
	public function build() {
		$stmt = $this->args["random"] ? $this->db->prepare("SELECT *, RAND() AS rnd FROM (SELECT * FROM rumours WHERE `deleted`=0 order by `id` desc LIMIT :limit) AS s ORDER BY rnd LIMIT 1 ;") : $this->db->prepare("SELECT * FROM rumours order by `id` desc LIMIT :limit ;");
		$stmt->bindParam(":limit",intval($this->args["number"]),PDO::PARAM_INT);
		try {
			$stmt->execute();
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}
		$rumours = $stmt->fetchAll();
		$this->assign("rumours",$rumours);
		$this->assign("isadmin", $this->getPermission() == _ADMIN_);

	}
}
?>
