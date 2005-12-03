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

class NetCVSectionList extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$app->addView("menu", "header_menu");
		
		$myNetCVUser = new NetCVUser($this->db, $this->currentUser, TRUE);

		if (isset($this->args['cvid'], $this->args['gid'])) {
			$myNetCVGroupList	= $myNetCVUser->returnCVGroupList();
			$myNetCVGroup		= $myNetCVGroupList->returnGroupById($this->args['gid']);
			$myNetCVSingleCV	= $myNetCVUser->returnSingleCV($this->args['gid'], $this->args['cvid']);

			$this->assign("myNetCVGroup", 	$myNetCVGroup);
			$this->assign("myNetCVSingleCV",	$myNetCVSingleCV);
			$this->assign("myNetCV",			$myNetCVSingleCV->getContent());			
			
			$this->assign("cvid",	$this->args['cvid']);
			$this->assign("gid",		$this->args['gid']);
		} else {
            //Aucun CV a charger
		}
		
		//Verification de la presence d'erreur et affection du message d'erreur a afficher
		$this->assign("netcvMessages", $this->formMessage->getSession());
		$this->formMessage->flush();
		
		$myNetCVLanguage = new NetCVLanguage();
		$this->assign("myNetCVLanguage",$myNetCVLanguage);
	}
}
?>
