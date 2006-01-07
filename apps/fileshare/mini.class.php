<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class FileShareMini extends Model
{
	public function build()
	{
		$myKDBFSElementFactory = new KDBFSElementFactory($this->db, $this->userFactory, $this->permission);
		$this->assign("lastAddedFiles", $myKDBFSElementFactory->getLastAddedFiles());
		$this->assign("mostDownloadedFiles", $myKDBFSElementFactory->getMostDownloadedFiles());
	}
}
?>
