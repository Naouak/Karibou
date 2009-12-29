<?php

/**
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/

class BugsAddMod extends Model
{

	public function build() {
   
		//Configuration pour obtenir les développeurs
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$devGroupId = $config["developersGroup"]["id"];

		$this->assign("devlist", $this->userFactory->getUsersFromGroup($this->userFactory->getGroupsFromId($devGroupId)));

	}
}