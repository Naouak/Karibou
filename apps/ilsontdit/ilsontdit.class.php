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
class Ilsontdit extends Model {
	public function build() {
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();

		$sql = "SELECT * FROM ilsontdit WHERE deleted=0 ORDER BY date_report DESC LIMIT :maxquotes ";
		try {
			$stmt = $this->db->prepare($sql);
			$maxquotes = 3;
			if (isset($this->args["maxquotes"]))
				$maxquotes = intval($this->args["maxquotes"]);
			$stmt->bindValue(":maxquotes", $maxquotes, PDO::PARAM_INT);
			$stmt->execute();
		} catch(PDOException $e) {
			Debug::kill($e->getMessage());
		}

		$quotes = array();
		while ($quoteRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
			if ($user["object"] =  $this->userFactory->prepareUserFromId($quoteRow["reporter"])) {
				$quote = array();
				$quote["author"] = $quoteRow["who"] . " (" . $quoteRow["group"] . ")";
				$quote["text"] = stripslashes($quoteRow["message"]);
				$quote["id"] = $quoteRow["id"];
				$quote["reporter"]=$quoteRow["reporter"];
				$quotes[] = $quote;
			}
		}
		$this->assign("quotes", $quotes);
		$this->assign("isadmin", $this->getPermission() == _ADMIN_);
		$this->assign("currentuser",$this->currentUser->getId());
	}
}

?>
