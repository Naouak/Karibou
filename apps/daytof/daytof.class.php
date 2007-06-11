<?php 
/**
 * @copyright 2005 JoN
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
class DayTof extends Model
{
	protected $max_width_daytof = 200;
	protected $max_height_daytof = 200;

	public function build()
	{
		$tofdir = KARIBOU_PUB_DIR.'/daytof';
		//controle + creation repertoire img
		if( ! is_dir( $tofdir ) )
		{
			mkdir( $tofdir );
		}


		
		//New photo
		if ( isset($_FILES['daytof_file'], $_POST['daytof_comment']) && ($this->currentUser->getID() > 0)) 
		{
		if( is_uploaded_file($_FILES['daytof_file']['tmp_name']) && filesize($_FILES['daytof_file']['tmp_name'])<1512000)
		{

			echo "11111";
			//Id nouvelle photo
			$sql = "SELECT id FROM daytof WHERE 1 ORDER BY id DESC LIMIT 1 ";
        		try
        		{
            			$stmt = $this->db->query($sql);
        		}
        		catch(PDOException $e)
        		{
            			Debug::kill($e->getMessage());
        		}
			if ($lastdaytof = $stmt->fetch(PDO::FETCH_ASSOC)) {
	        		$new_id=$lastdaytof["id"] + 1;
			}
		

			
			//copie du du fichier & miniature
			$tof_file = 'PIC';
			if(($new_id)<10) $tof_file.="0000".$new_id;
			elseif(($new_id)<100) $tof_file.="000".$new_id;
			elseif(($new_id)<1000) $tof_file.="00".$new_id;
			elseif(($new_id)<10000) $tof_file.="0".$new_id;
			else $tof_file.=$new_id;

			$param_file = getimagesize($_FILES['daytof_file']['tmp_name']);
			
			switch( $param_file[2] ) //1gif 2jpg 3png
			{
				case '3':
					$im = imagecreatefrompng($_FILES['daytof_file']['tmp_name']);
					break;
				case '2':
					$im = imagecreatefromjpeg($_FILES['daytof_file']['tmp_name']);
					break;
				case '1':
					$im = imagecreatefromgif($_FILES['daytof_file']['tmp_name']);
					break;
				default:
					$im = false;
					break;
			}
			if($im)
			{
				
				//sauvegarde
				imagepng($im, $tofdir.'/'.$tof_file.'.png', 0);

				//miniature					
				$x = imagesx($im);
				$y = imagesy($im);
				
				$x_ratio = $this->max_width_daytof / $x;
				$y_ratio = $this->max_height_daytof / $y;
				
				if( $x_ratio > $y_ratio )
				{
					$new_x = $x * $y_ratio;
					$new_y = $y * $y_ratio;
				}
				else
				{
					$new_x = $x * $x_ratio;
					$new_y = $y * $x_ratio;
				}
				$new_im = imagecreatetruecolor($new_x, $new_y);
				imagecopyresampled($new_im, $im, 0, 0, 0, 0, $new_x, $new_y, $x, $y);
				imagedestroy($im);
				imagepng($new_im, $tofdir.'/m'.$tof_file.'.png', 0);
				imagedestroy($new_im);
				
				$currentUser = $this->userFactory->getCurrentUser();
				
				if($_POST['daytof_comment']=="") $daytof_comment="RAS";
				else $daytof_comment=$_POST['daytof_comment'];

				//requete
				$sql = "INSERT INTO daytof (user_id, photo, comment, datetime) VALUES ('".$this->currentUser->getID()."','".$tof_file."','".strip_tags($daytof_comment)."', NOW());";
				$this->db->exec($sql);
				//unlink($tof_file.'.png');
			}
			else 
			{
				$erreur_daytof="File type error";
				$this->assign("erreur_daytof",$erreur_daytof);
			}

			
		}
		else 
		{
			$erreur_daytof="Sending error";
			$this->assign("erreur_daytof",$erreur_daytof);
		}
}

		
		//day tof, photo du jour
		$sql = "SELECT * FROM daytof WHERE 1 ORDER BY datetime DESC LIMIT 1 ";
        	try
        	{
            		$stmt = $this->db->query($sql);
        	}
        	catch(PDOException $e)
        	{
            		Debug::kill($e->getMessage());
        	}
		
		if ($daytofonline = $stmt->fetch(PDO::FETCH_ASSOC)) {
	        //je recupere l'user
   		    if ($user =  $this->userFactory->prepareUserFromId($daytofonline["user_id"])) {

	        	$this->assign("tof",('/pub/daytof/m'.$daytofonline["photo"].'.png'));
	        	$this->assign("linktof",('/pub/daytof/'.$daytofonline["photo"].'.png'));
		       $this->assign("daytofauthor",$user);
			$this->assign("daytofcomment",$daytofonline["comment"]);
			}
		}


		
	}
}

?>
