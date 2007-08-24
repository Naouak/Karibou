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
class FileShareDetails extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$app->addView("menu", "header_menu", array("page"=>"details"));

		if (isset($this->args["elementpath"]) && $this->args["elementpath"] != "")
		{
			$element = new KDBFSElement($this->db, $this->userFactory, $this->permission, FALSE, base64_decode($this->args["elementpath"]));
			
			if ($element->isDirectory())
			{
				$element = new KDirectory ($this->db, $this->userFactory, $this->permission, base64_decode($this->args["elementpath"]));
			}
			elseif ($element->isFile())
			{
				$element = new KFile ($this->db, $this->userFactory, $this->permission, base64_decode($this->args["elementpath"]));			
			}
			else
			{
				$element = NULL;
			}
			
			$this->assign("myElement", $element);
		}
	}
}

?>
