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
class FileShareCreateDirectory extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$app->addView("menu", "header_menu", array("page"=>"createdirectory"));

		if (isset($this->args['directoryname']) && $this->args['directoryname'] != '')
		{
			$dirname = base64_decode($this->args['directoryname']);
		}
		else
		{
			$dirname = '';
		}
		
		$myDir = new KDirectory($this->db, $this->userFactory, $this->permission, $dirname);
		if ($myDir->canWrite())
		{
			$this->assign('allowed', TRUE);
			if ( isset($this->args['directoryname']) )
			{
				$this->assign('directorynamebase64', $this->args['directoryname']);
				$this->assign('directoryname', base64_decode($this->args['directoryname']));
			}
			$groups = $this->userFactory->getGroups();
			$this->assign('grouptree', $groups->getTree() );
		}
	}
}

?>