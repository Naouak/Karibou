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

class WikiHistory extends Model
{
    public $current_page = '';
    
    public function build(){
    
        if(isset($this->args['docu']) && $this->args['docu'] != '') {
			$this->current_page = $this->args['docu'];
        }
		else {
			$this->current_page = 'Accueil';
        }
               
        $o_wiki = $this->getDataWiki();
        
        
        $t_history = $o_wiki->history($this->db);
    }
    
    public function getDataWiki(){
        
        WikiFactory::init($this->db);
        $o_wiki = WikiFactory::select($this->current_page);
        
        return $o_wiki;
    }
}
?>
