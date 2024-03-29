<?php 
/**
 * @copyright 2008 Pinaraf
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
class DaTof extends Model
{
	public function build()
	{
		/**
		* The parameter tofnum is special...
		* 0 => current tof
		* 1 => previous
		* 2 => previous^2
		**/
		if (isset($this->args['tofnum']) && strlen($this->args['tofnum'] > 0)) {
			$tofnum = $this->args['tofnum'];
		} else {
			$tofnum = 0;
		}
		
		// We'll use the SQL LIMIT SELECT query argument to filter... So we must increment tofnum.
		$tofnum++;
		
		//===>AFFICHAGE day tof, photo du jour
		try
		{
			$stmt = $this->db->prepare("SELECT * FROM daytof WHERE deleted=0 ORDER BY datetime DESC LIMIT :tofnum");
			$stmt->bindValue(":tofnum", $tofnum, PDO::PARAM_INT);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		$row = FALSE;

		for ($i = 0 ; $i < $tofnum ; $i++) {
			$temp = $stmt->fetch();
			if ($temp !== FALSE)
				$row = $temp;
		}
		// $row now contains the tof infos...
		if ($row !== FALSE) {
			$tofdir = KARIBOU_PUB_DIR.'/daytof';
	
			if ($user["object"] =  $this->userFactory->prepareUserFromId($row["user_id"])) {
				$name=$this->appname."-".$row['id'];
				$file = "PIC" . str_pad($row["id"], 5, "0", STR_PAD_LEFT);
				$path = "$tofdir/$file";
	
				/* Here we test if the .png file exists, because at some point we
					switched from PNG to JPEG because of file size issues.
				        Also, .gif files aren't converted to keep animations. */
				if (is_readable("$path.png")) {
					$filename = "$file.png";
					$smallName = "m$file.png";
				} else if (is_readable("$path.gif")) {
					$filename = "$file.gif";
					$smallName = "m$file.jpg";
				} else {
					$filename = "$file.jpg";
					$smallName = "m$file.jpg";
				}

				$this->assign("id",$row["id"]);
				$this->assign("tof", ('pub/daytof/' . $smallName));
				$this->assign("linktof", ('pub/daytof/' . $filename));
				$this->assign("datofauthor", $user);
				$this->assign("datofcomment", $row["comment"]);
				$this->assign("islogged", $this->currentUser->isLogged());

                                $combox = new CommentSource($this->db,$name,$row["comment"],
                                    "<a class='lightbox' href='".'pub/daytof/' . $filename."'>"."<img src='".'pub/daytof/' . $smallName."' /></a>");
				

				$this->assign("idcombox", $combox->getId());
				$this->assign("author_id",$row["user_id"]);
				$this->assign("isadmin", $this->getPermission() == _ADMIN_);
				$this->assign("currentuser",$this->currentUser->getId());
			}
		} else {
			$this->assign("missingPicture", true);
		}
	}
}

?>
