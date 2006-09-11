<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 
 
class NetCVGroupModify extends Model
{
	public function build()
	{

		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "cvgroupmodify") );

		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		//$this->assign("config", $config);
		$this->assign("config", $GLOBALS['config']['netcv']);
	
		$myNetCVUser = new NetCVUser($this->db, $this->currentUser, TRUE);
		$myNetCVUser->getCVGroupList();

		if (isset($this->args['gid']))
		{
			$this->assign("gid", $this->args['gid']);
		}
		
		//Verification de la presence d'erreur et affection du message d'erreur a afficher
		$this->assign("netcvMessages", $this->formMessage->getSession());
		$this->formMessage->flush();
		

		$skinList = new NetCVSkinList($this->db);
		$this->assign("myNetCVSkinList", $skinList->getSkinList());
		
		$myNetCVGroupList = $myNetCVUser->returnCVGroupList();
		$this->assign("myNetCVGroupList", $myNetCVUser->returnCVGroupList());
		$this->assign("currentGroupInfo", $this->getCurrentGroupInfo());
		unset($_SESSION["netcvGroup"]);
	}
	
	function getCurrentGroupInfo () {
        if (isset($_SESSION["netcvGroup"]))
        {
        		return array (
        			"title"			=> $_SESSION["netcvGroup"]["title"],
        			"hostname"		=> $_SESSION["netcvGroup"]["hostname"],
        			"diffusion"		=> $_SESSION["netcvGroup"]["diffusion"],
        			"emaildisplay"	=> $_SESSION["netcvGroup"]["emaildisplay"],
        			"design"		=> $_SESSION["netcvGroup"]["design"]);
        }
		else
		{
			return array();
		}
	}
}
?>
