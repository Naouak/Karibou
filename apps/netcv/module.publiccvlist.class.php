<?php

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/


class NetCVModulePublicCVList extends Model
{
	public function build()
	{
		if (isset($this->args['userid']) && $this->args['userid'] != '')
		{
			$app = $this->appList->getApp($this->appname);
			$config = $app->getConfig();
			$this->assign("config", $GLOBALS['config']['netcv']);

			$myNetCVUser = new NetCVUser($this->db, $this->args['userid'], TRUE);
			$myNetCVGroupList = $myNetCVUser->returnCVGroupList();

			//Verification de la presence d'erreur et affection du message d'erreur a afficher
			//$this->assign("netcvMessages", $this->formMessage->getSession());
			//$this->formMessage->flush();

			$this->assign("groups", $myNetCVGroupList->returnGroupsObjects());

			$myNetCVLanguage = new NetCVLanguage();
			$this->assign("myNetCVLanguage", $myNetCVLanguage);
		}
	}
}
?>
