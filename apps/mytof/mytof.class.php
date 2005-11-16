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
class MyTof extends Model
{
	protected $max_width = 200;
	protected $max_height = 320;

	public function build()
	{
		$tofdir = KARIBOU_PUB_DIR.'/mytof';
		if( ! is_dir( $tofdir ) )
		{
			mkdir( $tofdir );
		}
		
		if( isset($this->args['tof_file'])  )
		{
			$tof_file = KARIBOU_PUB_DIR.'/'.$this->args['tof_file']['name'];
			
			switch( strtolower($this->args['tof_file']['type']) )
			{
				case 'image/png':
					$im = imagecreatefrompng($tof_file);
					$newfilename = basename(strtolower($this->args['tof_file']['name']), '.png').'.jpg';
					break;
				case 'image/jpeg':
					$im = imagecreatefromjpeg($tof_file);
					$newfilename = basename(strtolower($this->args['tof_file']['name']), '.jpg').'.jpg';
					break;
				case 'image/gif':
					$im = imagecreatefromgif($tof_file);
					$newfilename = basename(strtolower($this->args['tof_file']['name']), '.gif').'.jpg';
					break;
				default:
					$im = false;
					break;
			}
			if($im)
			{
				$x = imagesx($im);
				$y = imagesy($im);
				
				$x_ratio = $this->max_width / $x;
				$y_ratio = $this->max_height / $y;
				
				if( $x_ratio > $x_ratio )
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
				imagejpeg($new_im, KARIBOU_PUB_DIR.'/mytof/'.$newfilename, 60);
				imagedestroy($new_im);
			}
			unlink($tof_file);
			$currentUser = $this->userFactory->getCurrentUser();
			$miniapps = $currentUser->getPref('miniapps') ;
			$args = array('miniappid' => $this->args['miniappid'] , 'tof' => 'mytof/'.$newfilename);
			$miniapps->setArgs( $this->args['miniappid'], $args );
			$currentUser->setPref('miniapps', $miniapps );
			$this->args['tof'] = 'mytof/'.$newfilename;
		}
		if( isset($this->args['tof']) )
		{
			$this->assign('tof', $this->args['tof']);
		}
	}
}

?>