<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 
 
class NetCVSingleCVModify extends Model
{
	public function build()
	{
	
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "cvsinglecvmodify") );
		
		$myNetCVLanguage = new NetCVLanguage();
		$this->assign("languages",$myNetCVLanguage->returnLanguages());
		

		if (isset($this->args['cvid'],$this->args['gid']) && $this->args['cvid'] != "" && $this->args['gid'] != "")
		{
			$myNetCVUser = new NetCVUser($this->db, $this->currentUser,TRUE);
			$myNetCVGroupList	= $myNetCVUser->returnCVGroupList();
			$myNetCVGroup		= $myNetCVGroupList->returnGroupById($this->args['gid']);
			$myNetCVSingleCV	= $myNetCVUser->returnSingleCV($this->args['gid'], $this->args['cvid']);

			$this->assign("myNetCVGroup", $myNetCVGroup);
			$this->assign("myNetCVSingleCV", $myNetCVSingleCV);
			//$this->assign("myNetCVSingleCV", $myNetCVUser->returnSingleCV($this->args['gid'],$this->args['cvid']));
			
			$this->assign("cvid", $this->args['cvid']);
		}
		
		if (isset($this->args['gid']) && $this->args['gid'] != "")
		{ 
			$this->assign("gid", $this->args['gid']);
		}
		
		//Verification de la presence d'erreur et affection du message d'erreur a afficher
		$this->assign("netcvMessages", $this->formMessage->getSession());
		$this->formMessage->flush();
		
	}
}

?>