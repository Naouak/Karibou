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
		if ( ($this->permission > _READ_ONLY_) )
		{
				if (isset($this->args['fileid']))
				{
					$file = new KFile($this->db, $this->userFactory, $this->permission, FALSE, FALSE, $this->args['fileid']);
					if ($file->canWrite())
					{
						$this->assign('myFile', $file);
						$this->assign('allowed', TRUE);
					}
					
				}
				elseif (isset($this->args['directoryname']))
				{
					$myDir = new KDirectory($this->db, $this->userFactory, $this->permission, base64_decode($this->args['directoryname']));
					if ($myDir->canWrite())
					{
						$this->assign('directorynamebase64', $this->args['directoryname']);
						$this->assign('directoryname', base64_decode($this->args['directoryname']));
						$this->assign('allowed', TRUE);
					}
				}
				
				$groups = $this->userFactory->getGroups();
				$this->assign('grouptree', $groups->getTree() );
		}
	}
}

?>
