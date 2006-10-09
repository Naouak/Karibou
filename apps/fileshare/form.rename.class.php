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
class FileShareFormRename extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$app->addView("menu", "header_menu", array("page"=>"rename"));
		
		$myKDBFSElementFactory = new KDBFSElementFactory($this->db, $this->userFactory, $this->permission);

        if (isset($this->args["elementid"]) && $this->args["elementid"] != "")
		{
			$myElement = new KDBFSElement($this->db, $this->userFactory, $this->permission, FALSE, FALSE, $this->args["elementid"]);
			if ($myElement->canWrite())
				$this->assign("myElement", $myElement);
			//$myDirectory = new KDirectory($this->db, $this->userFactory, $this->permission, "", FALSE, FALSE, FALSE);
			//$this->assign("myDirectoryTree", $myDirectory->returnTree());
		}
	}
	
}

?>