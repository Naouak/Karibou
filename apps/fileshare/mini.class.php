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
		
		if( isset($this->args['maxfilesadd']) && $this->args['maxfilesadd'] != "")
		{
			$maxadd = $this->args['maxfilesadd'];
		}
		else
			$maxadd = 3;
		
		
		if( isset($this->args['maxfilesdown']) && $this->args['maxfilesdown'] != "")
		{
			$maxdown = $this->args['maxfilesdown'];
		}
		else 
			$maxdown = 3;
		
		
		$myKDBFSElementFactory = new KDBFSElementFactory($this->db, $this->userFactory, $this->permission);
		$this->assign("lastAddedFiles", $myKDBFSElementFactory->getLastAddedFiles($maxadd));
		$this->assign("mostDownloadedFiles", $myKDBFSElementFactory->getMostDownloadedFiles($maxdown));
		$this->assign("islogged", $this->currentUser->isLogged());
		

	}
}
?>
