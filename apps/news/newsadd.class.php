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
        $this->currentuser = $this->userFactory->getCurrentUser() ;
		$app = $this->appList->getApp($this->appname);
		$app->addView("menu", "header_menu", array("page" => "add") );
		
		$this->assign('permission', $this->permission);
		
		//Verification de la presence d'erreur et affection du message d'erreur a afficher
		$this->assign("theNewsMessages", $this->formMessage->getSession());
		$this->formMessage->flush();


        $admingroups = $this->currentuser->getAllAdminGroups($this->db);
        $array = array();
        $i=0;
        foreach ($admingroups as $key)
        {

            $array[$i]["name"] = $key->getName();
            $array[$i]["Id"] = $key->getId();
            $i++;
            
        }
        $this->assign('Admin',$array);
	}
}

?>
