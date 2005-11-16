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
class FileShareList extends Model
{
	public function build()
	{
		$myDir = new KDirectory(KARIBOU_PUB_DIR.'/fileshare/');
		$this->assign("files",$myDir->returnFileList());
	}
}

?>
