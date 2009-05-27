<?php

class DaytofSubmit extends AppContentModel {
	private $max_width_daytof = 200;
	private $max_height_daytof = 200;
	public function submit($parameters) {
		$tofdir = KARIBOU_PUB_DIR.'/daytof';
		// If the images folder doesn't exist, create it.
		if( ! is_dir( $tofdir ) )
			mkdir( $tofdir, 0744, true);

		$tmpFileName = $parameters['file']['tmp_name'];
		if( is_uploaded_file($tmpFileName) && filesize($tmpFileName)<1512000)
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

			$im = imagecreatefromstring(file_get_contents($tmpFileName));
			if($im)
			{
				//sauvegarde
				imagejpeg($im, $tofdir.'/'.$tof_file.'.jpg', 95);
				
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
				imagejpeg($new_im, $tofdir.'/m'.$tof_file.'.jpg', 95);
				imagedestroy($new_im);
				
				if($parameters['comment']=="") $daytof_comment="RAS";
				else $daytof_comment=$parameters['comment'];
				
				// Insert into the database.
				$stmt = $this->db->prepare("INSERT INTO daytof (id, user_id, comment, datetime) VALUES(:id, :user, :comment, NOW())");
				$stmt->bindValue(":id", $new_id);
				$stmt->bindValue(":user", $this->currentUser->getID());
				$stmt->bindValue(":comment", strip_tags($daytof_comment));
				$stmt->execute();
			}
			else 
			{
				$this->assign("daytofError", "File type error");
			}
		}
	}

	public function formFields() {
		return array("file" => array("type" => "file", "required" => true, "label" => "Fichier photo"),
			"comment" => array("type" => "text", "label" => "Commentaire :"));
	}
}

?>
