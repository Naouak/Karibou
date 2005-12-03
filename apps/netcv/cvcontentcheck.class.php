<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 

class NetCVContentCheck extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$app->addView("menu", "header_menu", array("page" => "cvcontentcheck"));
		$config = $app->getConfig();

		$myNetCVUser = new NetCVUser($this->db, $this->currentUser);
		$myNetCVGroupList = $myNetCVUser->returnCVGroupList();
		
		if (isset($this->args["cvid"],$this->args["gid"]) && $this->args["cvid"] != "" && $this->args["gid"] != "") {
			$myNetCVGroup = $myNetCVGroupList->returnGroupById($this->args['gid']);
			$myNetCVSingleCVList = $myNetCVGroup->returnCVListObject();
			$myNetCVSingleCV = $myNetCVGroup->CVList->returnCVById($this->args["cvid"]);
			$myNetCV = $myNetCVSingleCV->getContent();
			$myNetCVPersonalInfo = $myNetCVUser->returnPersonalInfo($this->args['cvid']);			
		
			$this->assign("gid", $this->args["gid"]);
			$this->assign("cvid", $this->args["cvid"]);
			
			$this->assign("myNetCVPersonalInfo", $myNetCVPersonalInfo);
			$this->assign("myNetCV", $myNetCV);
			$this->assign("myNetCVSingleCV", $myNetCVSingleCV);
			$this->assign("myNetCVSingleCVList", $myNetCVSingleCVList->returnCVList());

		} else {
			
		}
		$this->assign("myNetCVGroupList", $myNetCVGroupList);
		$this->assign("myNetCVUser", $myNetCVUser);
		$this->assign("config", $config);
	}
}

?>