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
	$today = date('Ymd');

		if (isset($_POST, $_POST['ddayevent']) && ($this->currentUser->getID() > 0) ) 
		{
			$event = strip_tags($_POST['ddayevent']); //strip_tags = enleve code html
			if ($_POST['ddayevent']!= "" && strlen($_POST['ddaydate'])== 10)
			{
				//control sur date:
				$ddaydate = str_replace("-","",$_POST['ddaydate']);
				if($ddaydate >= $today)
				{
					//Requete d'insertion
					$sql = "INSERT INTO dday (user_id, event, date) VALUES ('".$this->currentUser->getID()."','".$event."', '".$ddaydate."');";
					$this->db->exec($sql);
				}
			}
			
		}


        $app = $this->appList->getApp($this->appname);
        $config = $app->getConfig();

      
        $sql = "SELECT *,ROUND((UNIX_TIMESTAMP(date)-UNIX_TIMESTAMP('".$today."'))/(60*60*24)) as JJ"
	." FROM dday WHERE (date >= '".$today."')"
	." ORDER BY date LIMIT 5";
 
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
