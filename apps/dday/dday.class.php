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
        $sql = "SELECT *, CONCAT(YEAR(`date`), MONTH(`date`), DAY(`date`)) AS vcalDate, (TO_DAYS(`date`) - TO_DAYS(CURRENT_DATE())) AS JJ FROM `dday` WHERE date >= CURRENT_DATE() ORDER BY date LIMIT 5";
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
