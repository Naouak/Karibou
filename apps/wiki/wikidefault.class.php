<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2003 Pierre Laden <pladen@elv.enic.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/
 
 
/**
 * Classe WikiDefault
 *
 * @package applications
 */

class WikiDefault extends Model
{
    
    public $current_page = '';
    public $mode = '';
    public $o_wiki = null;
    
	public function build()	{     
	
        if(isset($this->args['docu']) && $this->args['docu'] != '') {
			$this->current_page = $this->args['docu'];
        }
		else {
			$this->current_page = 'Accueil';
        }
        
        if(isset($this->args['mode']) && $this->args['mode'] != '') {
			$this->mode = $this->args['mode'];
        }
        
	$menuApp = $this->appList->getApp($this->appname);
	$menuApp->addView("menu", "header_menu", array("page" => "home", "docu" => $this->current_page, "mode" => $this->mode) );
	
        $this->o_wiki = $this->getDataWiki();
        
        switch($this->mode) {
            case 'edit' :
                $contenu_wiki = $this->o_wiki->getContent();                
                break;
            case 'preview' :
                debug::display($_POST);
                break;
            case 'add' :
                if ($this->add()){
                    $this->assign('msg', 'Modification réussie');
                }
                else {
                    $this->assign('msg', 'Une erreur s\'est produite.');            
                }
                $this->o_wiki = $this->getDataWiki();
                $contenu_wiki = $this->o_wiki->getContentXhtml();
                break;
            case 'history' :
                $contenu_wiki = $this->o_wiki->getContentXhtml();
                $t_history = $this->o_wiki->history($this->db);
                $this->assign('history', $t_history);
                break;
            default :
                $contenu_wiki = $this->o_wiki->getContentXhtml();
                break;
        }
        
               
        if ( $contenu_wiki == '' ) {
            $contenu_wiki = 'Cette page n`existe pas, vous pouvez la créer';
        }        
        
        $titre_wiki = $this->current_page;
        
        $debug = var_export($_POST, true);
        $this->assign('contenu_wiki', $contenu_wiki);
        $this->assign('titre_wiki', $titre_wiki);
        $this->assign('mode', $this->mode); 
        $this->assign('permission', $this->permission);
        debug::display('permission'.$this->permission);
	}
    
    public function getDataWiki(){
        
        WikiFactory::init($this->db);
        $o_wiki = WikiFactory::select($this->current_page);
        
        return $o_wiki;
    }
    
    public function add() {
        if ( isset($_POST) && isset($_POST['submit']) && $_POST['submit'] != '' ) {
            debug::display('toto'.$this->currentUser->getID());
            $date = date('Y-m-d H:i:s');            
            
            $o_wiki = new Wiki($_POST['page_wiki'], $_POST['contenu_wiki'], $date, $this->userFactory->prepareUserFromId($this->currentUser->getID()));
            $o_wiki->add($this->db);
            return true;
        }
        else {
            return false;
        }
    
    }
}

?>
