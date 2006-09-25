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
		$app = $this->appList->getApp($this->appname);

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

			$myDir = new KDirectory($this->db, $this->userFactory, $this->permission, $path);
		}
		else
		{
			$myDir = new KDirectory($this->db, $this->userFactory, $this->permission);
		}

		$app->addView("menu", "header_menu", array("page"=>"directory", "canUpdate" => $myDir->canUpdate(), "elementid" => $myDir->getSysInfos('id'), "myDirPathBase64"=>$myDir->getPathBase64(), "permission"=>$this->permission, "folderExistsInDB" => $myDir->existsInDB()||$myDir->isRootDir() ));

		$this->assign("myDir",$myDir);
		
		if ($this->permission > _READ_ONLY_)
		{
			$this->assign("uploadallowed", TRUE);
		}
		
		$this->assign("viewtype", "detailed");
	}
}

?>