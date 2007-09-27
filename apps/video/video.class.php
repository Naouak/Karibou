<?php 
/**
 * @copyright 2005 Simon Lehembre
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
class Video extends Model
{
    public function build()
    {
		if (isset($_POST, $_POST['newvideo']) && ($this->currentUser->getID() > 0) ) 
		{
			$video = $_POST['newvideo'];

			// Enregistrement URL : Youtube ou Dailymotion
			$site = $_POST['site'];
			if ( (strcmp($site, "youtube") == 0) && (strpos($video, "http://www.dailymotion.com/") == -1) )   {
				$urlvid="http://youtube.com/v/";
			} else {
				$urlvid="http://www.dailymotion.com/swf/";
				if (strpos($video, "http://www.dailymotion.com/") == 0) {
					// The URL given seems to be a full URL...
					// so open it and extract the right video ID...
					$file = fopen ($video, "r");
					if ($file) {
						while (!feof($file)) {
							$line = fgets($file, 1024);
							if (eregi ("<link rel=\"video_src\" href=\"http://www.dailymotion.com/swf/(.*)\" />", $line, $out)) {
								$video = $out[1];
								break;
							}
						}
						fclose($file);
					}
				}
			}


			$comment = strip_tags($_POST['comment']); //strip_tags = enleve code html
			if (strlen($video) > 3)
			{
				//Requete d'insertion
				$sql = "INSERT INTO video (user_id, video, site, comment, datetime) VALUES ('".$this->currentUser->getID()."','".$video."','".$urlvid."','".$comment."', NOW());";
				$this->db->exec($sql);
			}
			
		}


        $app = $this->appList->getApp($this->appname);
        $config = $app->getConfig();

       //duree de vie min d une video => sa sert a rien ce truc ??
	   //$min_t2l = $config["max"]["time2live"];	

        $sql = "SELECT * FROM video WHERE (UNIX_TIMESTAMP(datetime) < '".(time()+1)."') ORDER BY datetime DESC LIMIT 5";
        try
        {
            $stmt = $this->db->query($sql);
        }
        catch(PDOException $e)
        {
            Debug::kill($e->getMessage());
        }
		if ($videoonline = $stmt->fetchall(PDO::FETCH_ASSOC)) {
	        //je recupere l'user
   		    if ($user["object"] =  $this->userFactory->prepareUserFromId($videoonline['0']["user_id"])) {

	        	$this->assign("videonow",$videoonline['0']["video"]);
				$this->assign("commentaire",$videoonline['0']["comment"]);
				$this->assign("url",$videoonline['0']["site"]);
//				if ( strcmp($videoonline['0']["site"] , "youtube") == 0 )   {
//					$this->assign("url","http://youtube.com/v/");
//				}
//				else {
//					$this->assign("url","http://www.dailymotion.com/swf/");
//				}

				$this->assign("videosarray",$videoonline);	
		   		$this->assign("videoauthor",$user);
				$this->assign("islogged", $this->currentUser->isLogged());
				//$temps = time();
				//$this->assign("letemps",$temps);
				//$affia = print_r($videoonline);
			
			}
			else
			{
				$this->assign("DDempty","Err empty");
			}


		}
    }
}

?>
