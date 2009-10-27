<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class NewsModify extends Model
{
	protected $article;
	public function build()
	{
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "modify") );
	
		$this->article = new News ($this->userFactory);
		$this->assign('permission', $this->permission);
				
		//Chargement de la news a modifier a partir du SQL
		$this->article->loadFromId($this->db, $this->args['id']);
		
        $this->currentuser = $this->userFactory->getCurrentUser() ;
        $admingroups = $this->currentuser->getAllAdminGroups($this->db);
        $groups = $this->userFactory->getGroups();
        $array = array();
        $i=0;
        foreach ($admingroups as $key)
        {
            $array[$i][name] = $key->getName();
            $array[$i][Id] = $key->getId();
            $i++;
        }
        $this->assign('Admin',$array);

		if (($this->article->getAuthorId() == $this->currentUser->getId()) || (isset($array)))
		{
			//Affectation des variables a afficher
			$this->assign("theNewsToModify", $this->article);
			$groups = $this->userFactory->getGroups();
			$this->assign('grouptree', $groups->getTree() );
		}
		else
		{
			$this->assign ("notauthorized", TRUE);
		}
	}
}

?>
