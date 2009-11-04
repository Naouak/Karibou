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
		$stmt = $this->args["random"] ? $this->db->prepare("SELECT *, RAND() AS rnd FROM (SELECT * FROM rumours WHERE deleted=0 order by `id` desc LIMIT :limit) AS s ORDER BY rnd LIMIT 1 ;") : $this->db->prepare("SELECT * FROM rumours WHERE deleted=0 order by `id` desc LIMIT :limit ;");
		$stmt->bindParam(":limit",intval($this->args["number"]),PDO::PARAM_INT);
		try {
			$stmt->execute();
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}
		
		$rumours = array();
		while($rumourRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$name=$this->appname."-".$rumourRow['id'];
			$combox = new CommentSource($this->db,$name,"",$rumourRow["rumours"]);
			$rumour = array();
			$rumour["rumours"] = $rumourRow["rumours"];
			$rumour["id"] = $rumourRow["id"];
			$rumour["idcombox"] = $combox->getId();
			$rumours[] = $rumour;
		}

			
		$this->assign("rumours",$rumours);
		$this->assign("isadmin", $this->getPermission() == _ADMIN_);

	}
}
?>
