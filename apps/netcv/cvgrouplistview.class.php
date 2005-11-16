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

class NetCVGroupListView extends Model
{
	public function build()
	{

		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$this->assign("config", $config);
		
		$myNetCVUser = new NetCVUser($this->db, $this->currentUser, FALSE);
		//$myNetCVUser->getCVGroupList();
		$myNetCVGroupList = $myNetCVUser->returnCVGroupList();

		//Verification de la presence d'erreur et affection du message d'erreur a afficher
		$this->assign("netcvMessages", $this->formMessage->getSession());
		$this->formMessage->flush();

		$this->assign("myNetCVGroupList", $myNetCVGroupList);

		$myNetCVLanguage = new NetCVLanguage();
		$this->assign("myNetCVLanguage", $myNetCVLanguage);
	}
}
?>
