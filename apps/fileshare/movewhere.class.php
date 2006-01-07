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
class FileShareMoveChooseWhere extends Model
{
	public function build()
	{		$app = $this->appList->getApp($this->appname);
		$app->addView("menu", "header_menu", array("page"=>"movewhere"));
		
		$myKDBFSElementFactory = new KDBFSElementFactory($this->db, $this->userFactory);
		
		if (isset($this->args["elementid"]) && $this->args["elementid"] != "")
		{
			$this->assign("myFile", new KFile($this->db, $this->userFactory, FALSE, FALSE, $this->args["elementid"]));
			$myDirectory = new KDirectory($this->db, $this->userFactory, "");
			$this->assign("myDirectoryTree", $myDirectory->returnTree());
		}
	}
	
}

?>