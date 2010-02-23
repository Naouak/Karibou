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
        $app = $this->appList->getApp($this->appname);
        $config = $app->getConfig();

        try
        {
            $stmt = $this->db->query("SELECT * FROM video WHERE datetime < NOW() AND deleted=0 ORDER BY datetime DESC LIMIT 5");
        }
        catch(PDOException $e)
        {
            Debug::kill($e->getMessage());
        }

	if ($videoonline = $stmt->fetchall(PDO::FETCH_ASSOC)) {
		if ($user["object"] =  $this->userFactory->prepareUserFromId($videoonline[0]["user_id"])) {
			$name=$this->appname."-".$videoonline[0]['id'];
			$combox = new CommentSource($this->db,$name,"",$videoonline[0]["video"]);

			$this->assign("idnow",$videoonline[0]["id"]);
			$this->assign("idcomboxnow",$combox->getId());
			$this->assign("videonow",$videoonline[0]["video"]);
			$this->assign("commentaire",$videoonline[0]["comment"]);
			$this->assign("url",$videoonline[0]["site"]);

			foreach ($videoonline as $id => $items) {
				$videoonline[$id]["user"] = $this->userFactory->prepareUserFromId($videoonline[$id]["user_id"]);
				$name=$this->appname."-".$videoonline[$id]['id'];
				$combox = new CommentSource($this->db,$name,"",$videoonline[$id]["video"]);
				$videoonline[$id]["idcombox"] = $combox->getId();

			}
			
			$this->assign("videosarray", $videoonline);
			$this->assign("videoauthor",$user);
			$this->assign("islogged", $this->currentUser->isLogged());
			$this->assign("isadmin", $this->getPermission() == _ADMIN_);
			
		}
		else
		{
			$this->assign("DDempty","Err empty");
		}
	}
    }
}

?>
