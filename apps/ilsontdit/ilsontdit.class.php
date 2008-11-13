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
	$quote = filter_input(INPUT_POST,"quote",FILTER_SANITIZE_SPECIAL_CHARS);
	$author = filter_input(INPUT_POST,"author",FILTER_SANITIZE_SPECIAL_CHARS);
	$group = filter_input(INPUT_POST,"group",FILTER_SANITIZE_SPECIAL_CHARS);
        if ($quote!==false && ($this->currentUser->getID() > 0) ) {
            if ((strlen($quote) > 3) && (strlen($author) > 2) && (strlen($group) > 2)) {
                $query = $this->db->prepare("INSERT INTO ilsontdit (`reporter`, `group`, `who`, `message`, `date_report`) VALUES (:reporter, :group, :who, :msg, NOW())");
                $query->bindValue(":reporter", $this->currentUser->getID());
                $query->bindValue(":group", $group);
                $query->bindValue(":who", $author);
                $query->bindValue(":msg", $quote);
                if (!$query->execute()) {
                    Debug::kill("Error while inserting !");
                }
            } else {
                Debug::kill("One field is missing");
            }
        }

        $app = $this->appList->getApp($this->appname);
        $config = $app->getConfig();

        $sql = "SELECT * FROM ilsontdit ORDER BY date_report DESC LIMIT 3 ";
        try {
            $stmt = $this->db->query($sql);
        } catch(PDOException $e) {
            Debug::kill($e->getMessage());
        }
	
        $quotes = array();
        while ($quoteRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($user["object"] =  $this->userFactory->prepareUserFromId($quoteRow["reporter"])) {
                $quote = array();
                $quote["author"] = $quoteRow["who"] . " (" . $quoteRow["group"] . ")";
                $quote["text"] = stripslashes($quoteRow["message"]);
                $quotes[] = $quote;
            }
        }
        $this->assign("islogged", $this->currentUser->isLogged());
        $this->assign("quotes", $quotes);
    }
}

?>
