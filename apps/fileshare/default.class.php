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
class FileShareDefault extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$app->addView("menu", "header_menu", array("page"=>"home"));
	
		$myDir = new KDirectory(KARIBOU_PUB_DIR.'/fileshare/');
$this->assign("myDir", $myDir);
		//$this->assign("files",$myDir->returnFileList());
		
		if ($this->permission > _READ_ONLY_)
		{
			$this->assign("uploadallowed", TRUE);
		}
	}
}

?>