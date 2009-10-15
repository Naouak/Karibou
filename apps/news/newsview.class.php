<?php 
/**
 * @version $Id:  newsmodify.class.php,v 0.1 2005/07/20 19:12:34 dat Exp $
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

require_once dirname(__FILE__)."/class/news.class.php";

class NewsView extends Model
{
	protected $article;

	public function build()
	{
		if (isset($this->args['displayComments']) && ($this->args['displayComments'] == 1) )
		{
			$viewcomments = true;
		}
		else
		{
			$viewcomments = false;
		}

		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "view", "viewcomments" => $viewcomments, "article_id" => $this->args['id']) );
	
		$this->article = new News ($this->userFactory);
		
		//Chargement de la news a modifier a partir du SQL
		//& affectation des variables a afficher
		if ($this->article->loadFromId($this->db, $this->args['id']))
		{
			//if ($this->currentUser->isInGroup($this->db, $this->article->getGroup()) || ($this->article->getAuthorId() == $this->currentUser->getId()) )
			{
				$this->assign("theArticle", $this->article);
				$this->assign("currentUserId", $this->currentUser->getId());
				//Verification de la presence d'erreur et affection du message d'erreur a afficher
				$this->assign("theNewsMessages", $this->formMessage->getSession());
				$this->formMessage->flush();

				if (isset($this->args["addcomment"]))
				{
					//Verification de la presence d'un commentaire poste et affectation de la variable
					$this->assign("theNewsCurrentComment", $this->getCurrentComment());
					$this->unsetCurrentComment();
					$this->assign("addComment", TRUE);
				}
				
				if (isset($this->args['displayComments'])) {
					$this->assign("displayComments", $this->args['displayComments']);
					if ( ($this->args["displayComments"] == 1) && ($this->article->getNbComments()>0) )
					{
						$this->article->loadComments();
						$this->assign("theArticleComments", $this->article->getComments());
					}
				}
				
			}
			//else
			{
			//	$this->assign("notingroup", TRUE);
				//L'utilisateur n'appartient pas au groupe de destination
			}

            // permet de récupérer le nom du groupe correspondant au groupe qui a posté la news
            foreach ($this->userFactory->getGroups() as $group)
            {
                if ($group->getId() == $this->article->getGroup())
                {
                    $this->assign("group",$group->getName());
                }

            }
            $this->assign("isadmin",$this->getPermission() == _ADMIN_);
		}
		else
		{
			//L'identifiant n'a pas été trouvé (aucune news à afficher)
		}
		
		$this->assign('permission', $this->permission);
        $this->assign('currentuser',$this->userFactory->getCurrentUser());
        $this->assign('db',$this->db);
        $this->currentuser = $this->userFactory->getCurrentUser();
        $this->groups = $this->currentuser->getGroups($this->db);
        $grouparray = array();
        foreach ($this->groups as $group2)
        {
            $idofgroup = $group2->getId();
            $grouparray[$idofgroup]=$group2->role;
        }
        $this->assign('grouparray',$grouparray);
	}
	
	//Retourne le contenu du commentaire en cours
	function getCurrentComment () {
		if (isset($_SESSION["currentComment"])) {
			return $_SESSION["currentComment"];
		} else {
			return FALSE;
		}
	}
	
	//Vide le contenu de la variable de session contenant le commentaire en cours
	function unsetCurrentComment() {
		unset($_SESSION["currentComment"]);
	}
	
}
?>
