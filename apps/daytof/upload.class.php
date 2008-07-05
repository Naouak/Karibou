<?php 
/**
 * @copyright 2008 Pierre Ducroquet <pinaraf@gmail.com>
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
class Upload extends Model
{
	protected $max_width_daytof = 200;
	protected $max_height_daytof = 200;

	public function build()
	{
		$tofdir = KARIBOU_PUB_DIR.'/daytof';
		// If the images folder doesn't exist, create it.
		if( ! is_dir( $tofdir ) )
		{
			mkdir( $tofdir, 0744, true);
		}
		
		// This view should be used only as a "POST" event for the upload form... So check whether there is a new photo...
		if ( isset($_FILES['daytof_file'], $_POST['daytof_comment']) && ($this->currentUser->getID() > 0)) 
		{
			if( is_uploaded_file($_FILES['daytof_file']['tmp_name']) && filesize($_FILES['daytof_file']['tmp_name'])<1512000)
			{
	
				// Get the id for the new picture
				try
				{
					$stmt = $this->db->query("SELECT MAX(id)+1 FROM daytof");
				}
				catch(PDOException $e)
				{
					Debug::kill($e->getMessage());
				}
				if ($lastdaytof = $stmt->fetch()) {
					$new_id = $lastdaytof[0];
				}
				
				// Generate the target file name.
				$tof_file = 'PIC' . str_pad($new_id, 5, "0", STR_PAD_LEFT);

				$im = imagecreatefromstring(file_get_contents($_FILES["daytof_file"]["tmp_name"]));
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
					
					if (get_magic_quotes_gpc())
						$daytof_comment = stripslashes($daytof_comment);
	
					// Insert into the database.
					$stmt = $this->db->prepare("INSERT INTO daytof (id, user_id, comment, datetime) VALUES(:id, :user, :comment, NOW())");
					$stmt->bindValue(":id", $new_id);
					$stmt->bindValue(":user", $this->currentUser->getID());
					$stmt->bindValue(":comment", strip_tags($daytof_comment));
					$stmt->execute();
				}
				else 
				{
					$this->assign("erreur_daytof", "File type error");
				}
	
				
			}
			else
			{
				$this->assign("erreur_daytof", "Sending error");
			}
		}
	}
}

?>
