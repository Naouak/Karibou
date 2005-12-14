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
class FileShareUpload extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$app->addView("menu", "header_menu", array("page"=>"uploadfile"));

		//Uploading a new file version
		if (isset($this->args['fileid']))
		{
			//$dbfsFile = new KDBFSElement($this->db, FALSE, FALSE, $this->args['fileid']);
			//$this->assign('dbfsFile', $dbfsFile);
			$file = new KFile($this->db, FALSE, FALSE, $this->args['fileid']);
			$this->assign('myFile', $file);
			
		}
		elseif (isset($this->args['directoryname']))
		{
			$this->assign('directorynamebase64', $this->args['directoryname']);
			$this->assign('directoryname', base64_decode($this->args['directoryname']));
		}

		$groups = $this->userFactory->getGroups();
		$this->assign('grouptree', $groups->getTree() );
	}
}

?>
