<?php
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *-
 * @package applications
 **/


/**
 * Classe commentaires
 *
 * @package applications
 */

class Commentaires extends Model {
	public function build() {
		$combox = new CommentSource($this->db,$this->args["id"]);
		// we get all old comments for this couple appname,id
		$sql = "SELECT c.*, IF(cr.user IS NULL, false, true) AS `read` FROM comment c LEFT JOIN comment_read cr ON cr.user=:user_id AND cr.comment = c.id WHERE c.key_id=:id  AND c.deleted = 0 ORDER BY `date` ASC";
		$sqlc = $this->db->prepare($sql);
		$sqlc->bindValue(":id", $this->args["id"]);
		$sqlc->bindValue(":user_id", $this->currentUser->getID());
		$sqlc->execute();
		$this->assign("name",$combox->getName());
		$this->assign("title",$combox->getTitle());
		$this->assign("content",$combox->getContent());
		$this->assign("id",$combox->getId());
		$existing = $sqlc->fetchAll();
		$coms = Array();
		foreach($existing as $k => $v){
			$v['user'] = $this->userFactory->prepareUserFromId($v['user']);
			$coms[] = $v;
		}
		$this->assign("existing", $coms);
		$this->assign("isadmin", $this->getPermission() == _ADMIN_);
		$this->assign("currentuser",$this->currentUser->getId());

		if ($this->currentUser->isLogged()) {
			$sql = "INSERT INTO comment_read(user, comment) 
				SELECT :user_id, c.id FROM comment c LEFT JOIN comment_read cr ON cr.user=:user_id AND cr.comment=c.id WHERE key_id=:id AND cr.user IS NULL";
			$sqlu = $this->db->prepare($sql);
			$sqlu->bindValue(":id", $this->args["id"]);
			$sqlu->bindValue(":user_id", $this->currentUser->getID());
			$sqlu->execute();
		}
	}
}
