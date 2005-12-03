<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class NewsAdd extends Model
{

	public function build()
	{
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "add") );
		
		$this->assign('permission', $this->permission);
		
		//Verification de la presence d'erreur et affection du message d'erreur a afficher
		$this->assign("theNewsMessages", $this->formMessage->getSession());
		$this->formMessage->flush();
		
		$groups = $this->userFactory->getGroups();
		$this->assign('grouptree', $groups->getTree() );
	}
}

?>