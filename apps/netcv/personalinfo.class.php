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

class NetCVPersonalInfo extends Model
{
	public function build()
	{
	
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$this->assign("config", $config);
	
    	$myNetCVUser = new NetCVUser($this->db, $this->currentUser,TRUE);
		$myNetCVGroupList = $myNetCVUser->returnCVGroupList();
		
    	$this->assign("myNetCVUser", $myNetCVUser);
		$this->assign("myNetCVGroupList", $myNetCVGroupList);

		if (isset($this->args["cvid"],$this->args["gid"]) && ($this->args["cvid"] != "") &&  ($this->args["gid"] != "")) {
			$myNetCVGroup = $myNetCVGroupList->returnGroupById($this->args["gid"]);
			$myNetCVSingleCVListObject = $myNetCVGroup->returnCVListObject();
			$myNetCVSingleCV = $myNetCVSingleCVListObject->returnCVById($this->args["cvid"]);
	    	$this->assign("myNetCVSingleCV", $myNetCVSingleCV);
	    	$this->assign("cvid", $this->args["cvid"]);
	    	$this->assign("gid", $this->args["gid"]);

		}

		$myNetCVLanguage = new NetCVLanguage();
		$this->assign("myNetCVLanguage",$myNetCVLanguage);

		//Verification de la presence d'erreur et affection du message d'erreur a afficher
		$this->assign("netcvMessages", $this->formMessage->getSession());
		$this->formMessage->flush();

	}
}
?>