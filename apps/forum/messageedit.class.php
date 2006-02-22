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
class KFMessageEdit extends Model
{
	public function build()
	{
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "messageedit") );

		if (isset($this->args["forumid"]) && $this->args["forumid"] != "")
		{
			$forumid = 1;
			$factory = new KFFactory ($this->db, $this->userFactory);
			$myForum = $factory->getForumById($this->args["forumid"]);	
			$this->assign("myForum", $myForum);
			if (isset($this->args["messageid"]) && $this->args["messageid"] != "")
			{
				$myMessage = $factory->getMessageById($this->args["forumid"], $this->args["messageid"]);
				$this->assign("myMessage", $myMessage);
			}
		}
	}
}

?>