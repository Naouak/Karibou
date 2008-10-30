<?php 
/**
 * @copyright 2007 Charles Anssens
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
class Dday extends Model
{
    public function build()
    {
        if (isset($_POST, $_POST['ddayevent']) && ($this->currentUser->getID() > 0) ) 
        {
            if (get_magic_quotes_gpc()) {
                $_POST['ddayevent'] = stripslashes($_POST['ddayevent']);
                $_POST['ddaydesc'] = stripslashes($_POST['ddaydesc']);
		$_POST['ddaydate'] = stripslashes($_POST['ddaydate']);
		$_POST['ddaylink'] = stripslashes($_POST['ddaylink']);
            }
            $event = strip_tags($_POST['ddayevent']); //strip_tags = enleve code html
            if ($_POST['ddayevent']!= "" && strlen($_POST['ddaydate'])== 10)
            {
                //control sur date:
                $ddaydate = str_replace("-", "", $_POST['ddaydate']);
                if ($ddaydate >= date('Ymd'))
                {
                    $stmt = $this->db->prepare('INSERT INTO dday (user_id, event, date, link, `desc`) VALUES (:user, :event, :date, :link, :desc)');
                    $stmt->bindValue(':user', $this->currentUser->getID());
                    $stmt->bindValue(':event', $event);
                    $stmt->bindValue(':date', $ddaydate);
                    $stmt->bindValue(':link', $ddaylink);
                    $stmt->bindValue(':desc', strip_tags($_POST['ddaydesc']));
                    $stmt->execute();
                }
            }
            
        }

        $sql = "SELECT *, (TO_DAYS(`date`) - TO_DAYS(CURRENT_DATE())) AS JJ FROM `dday` WHERE date >= CURRENT_DATE() ORDER BY date LIMIT 5";
        try
        {
            $stmt = $this->db->query($sql);
        }
        catch(PDOException $e)
        {
            Debug::kill($e->getMessage());
        }
        
        if($ddaylist = $stmt->fetchAll(PDO::FETCH_ASSOC))
        {
            $this->assign("ddaylist",$ddaylist);
        }
        else
        {
            $this->assign("DDempty","Err empty");
        }
    }
}

?>
