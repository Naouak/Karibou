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
class FileShareDirectory extends Model
{
	public function build()
	{
		if (isset($this->args["directoryname"]) && $this->args["directoryname"] != "")
		{
			$path = '';
			foreach (split("/", base64_decode($this->args["directoryname"])) as $directory )
			{
				if ($directory != '')
				{
					$path .= $directory."/";
				}
			}

			$myDir = new KDirectory($path);
		}
		else
		{
			$myDir = new KDirectory();
		}

		$this->assign("myDir",$myDir);
		
		if ($this->permission > _READ_ONLY_)
		{
			$this->assign("uploadallowed", TRUE);
		}
		
		if ($this->permission > _READ_ONLY_)
		{
			$this->assign("uploadallowed", TRUE);
		}		
	}
}

?>
