<?php 
/**
 * @copyright 2008 Pierre Ducroquet
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/** 
 * 
 * @package applications
 **/
class linkshare extends Model {
	public function build() {
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();

		$sql = "SELECT * FROM linkshare WHERE `deleted`=0 ORDER BY date DESC LIMIT :maxlink ";
		try {
			$stmt = $this->db->prepare($sql);
			$maxlink = 3;
			if (isset($this->args["maxlink"]))
				$maxlink = intval($this->args["maxlink"]);
			$stmt->bindValue(":maxlink", $maxlink, PDO::PARAM_INT);
			$stmt->execute();
		} catch(PDOException $e) {
			Debug::kill($e->getMessage());
		}

		$links = array();
		while ($linkRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
			if ($user =  $this->userFactory->prepareUserFromId($linkRow["reporter"])) {
				$name=$this->appname."-".$linkRow['id'];
				$combox = new CommentSource($this->db,$name,"",$linkRow["link"]);
				
				$link = array();
				$link["idcombox"]=$combox->getId();
				$link["author"] = $user;
				$link["link"] = stripslashes($linkRow["link"]);
				$link["title"] = $linkRow["title"];
				$link["id"] = $linkRow["id"];
				$links[] = $link;
			}
		}
		$this->assign("idcombox",$combox->getId());
		$this->assign("isadmin", $this->getPermission() == _ADMIN_);
		$this->assign("currentuser",$this->currentUser->getId());
		$this->assign("isLogged", $this->currentUser->isLogged());
		$this->assign("links", $links);
	}
}

?>
