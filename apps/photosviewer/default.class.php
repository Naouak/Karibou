<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
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
class PhotosViewerDefault extends Model
{
	public function build()
	{
		$path = KARIBOU_PUB_DIR.'/photos/'.$this->args['path'].'/small';
		if (isset($this->args['path']) && $this->args['path'] != '' && is_dir($path))
		{
			$photos = $this->scan($path);
		
			$this->assign('path', $this->args['path']);
			$this->assign('photos', $photos);
		}
		else
		{
			echo $path;
			die;
		}
	}
	
	public function scan($dir)
	{
		$d = dir($dir);

		$files = array();
		while(FALSE !== ($entry = $d->read()))
		{
			if ($entry != "." && $entry != "..")
			{
				if (is_dir($entry))
				{
					$files = array_merge($files, $this->scan($entry));
				}
				elseif (is_file($dir.'/'.$entry)  && preg_match('/\.jpg/i', $entry) )
				{
					$files[] = $entry;
				}
			}
		}
		return $files;
	}
}
?>
