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
		$video = filter_input(INPUT_POST, 'newvideo', FILTER_SANITIZE_SPECIAL_CHARS);	
		$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);	

		// Enregistrement URL : Youtube ou Dailymotion
		$site = "unknown";
		if (eregi("http://(.*)youtube.com/watch\?v=(.*)", $video, $out)) {
			$video = $out[2];
			$urlvid = "http://www.youtube.com/v/";
			$site = "youtube";
		} else if (eregi("http://(.*)vimeo.com/(.*)", $video, $out)) {
			$video = $out[2];
			$urlvid = "http://vimeo.com/moogaloop.swf?clip_id=";
			$site = "vimeo";	
		} else if (eregi("http://(.*)dailymotion.com/(.*)", $video, $out)) {
			$urlvid = "http://www.dailymotion.com/swf/";
			$site = "dailymotion";
			
			$file = fopen ($video, "r");
			if ($file) {
				while (!feof($file)) {
					$line = fgets($file, 1024);
					if (preg_match ("<link rel=\"video_src\" href=\"http://www.dailymotion.com/swf/([^\?]*)(.*)?\" />", $line, $out)) {
						$video = $out[1];
						break;
					}
				}
				fclose($file);
			}
		}
		
		if ((strlen($video) > 3) && ($site != "unknown"))
		{
			// Requete d'insertion
			$sql = "INSERT INTO video (`datetime`, user_id, video, site, comment) VALUES (NOW(), :user, :vid, :url, :comment)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":user", $this->currentUser->getID());
			$stmt->bindValue(":vid", $video);
			$stmt->bindValue(":url", $urlvid);
			$stmt->bindValue(":comment", $comment);
			$stmt->execute();
		}
	}

        $app = $this->appList->getApp($this->appname);
        $config = $app->getConfig();

        $sql = "SELECT * FROM video WHERE datetime < NOW() ORDER BY datetime DESC LIMIT 5";
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
		if ($user["object"] =  $this->userFactory->prepareUserFromId($videoonline[0]["user_id"])) {
			$this->assign("videonow",$videoonline[0]["video"]);
			$this->assign("commentaire",$videoonline[0]["comment"]);
			$this->assign("url",$videoonline[0]["site"]);

			foreach ($videoonline as $id => $items) {
				$videoonline[$id]["user"] = $this->userFactory->prepareUserFromId($videoonline[$id]["user_id"]);
			}
		   	$this->assign("videosarray", $videoonline);
			$this->assign("videoauthor",$user);
			$this->assign("islogged", $this->currentUser->isLogged());
		}
		else
		{
			$this->assign("DDempty","Err empty");
		}
	}
    }
}

?>
