<?php 

/**
 * @version $Id:  newsaddcomment.class.php,v 0.1 2005/06/26 10:52:56 dat Exp $
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

require_once dirname(__FILE__)."/class/news.class.php";

class NewsAddComment extends Model
{
	public function build()
	{
		$this->article = new News ($this->userFactory);
		
		//Chargement de la news a modifier a partir du SQL
		$loadArticleResult = $this->article->loadFromId($this->db, $this->args['id']);
		
		//Affectation des variables a afficher
		if ($loadArticleResult) {
			$this->assign("theArticle", $this->article);
		} else {
			//L'identifiant n'a pas été trouvé (aucune news à afficher)
		}

		//Verification de la presence d'erreur et affection du message d'erreur a afficher
		$this->assign("theNewsMessages", $this->formMessage->getSession());
		$this->formMessage->flush();

		//Verification de la presence d'un commentaire poste et affectation de la variable
		$this->assign("theNewsCurrentComment", $this->getCurrentComment());
		$this->unsetCurrentComment();
/*
		$this->hookManager->add("page_contenu_start", "<div id=\"news\">");
		$this->hookManager->add("page_contenu_end", "</div>");
*/		
		$this->assign('permission', $this->permission);
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
