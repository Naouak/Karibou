<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 
 
$GLOBALS['myTranslation'] = new NetCVTempTranslation();

class NetCVGroupDelete extends Model
{
	public function build()
	{
		$myNetCVUser = new NetCVUser($this->db, $this->currentUser, TRUE);
        $myNetCVUser->getCVGroupList();
        $myNetCVGroupList = $myNetCVUser->returnCVGroupList();
        
        if (isset($this->args['gid'])) {
			$myNetCVGroup = $myNetCVGroupList->returnGroupById ($this->args['gid']);
			$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("DELETED_CV"));
			$this->formMessage->setSession();

			$this->assign("gid", $this->args['gid']);
			$myNetCVGroupList->deleteGroup($this->args['gid']);
		}
		$this->assign("myNetCVGroup", $myNetCVUser->returnCVGroupList());
	}
}
?>
