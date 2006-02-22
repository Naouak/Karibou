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
class KFDefault extends Model
{
	public function build()
	{
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "default") );

		$forumid = 1;

		$factory = new KFFactory ($this->db, $this->userFactory);
		
		$myForum = $factory->getForumById($forumid);
		$this->assign("myForum", $myForum);
				
		$myMessages = $factory->getMessageList($forumid);
		$this->assign("myMessages", $myMessages);
	}
}

?>