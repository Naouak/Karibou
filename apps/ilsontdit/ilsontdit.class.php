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
