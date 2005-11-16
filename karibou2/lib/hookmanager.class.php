<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

/**
 * Gestion des ancres pour l'affichage de textes a l'exterieur des template du Model
 *
 * Il faut ajouter la ligne $this->assign("hookManager", $this->hookManager); dans
 * la methode build() des modèles qui font appel à
 *
 * @todo move that file to framework/class directory
 * @todo indent properly
 * @package framework
 */
class HookManager
{
	public $hooks; //PropriÃ©tÃ© contenant les accroches de page
	
	/*
	 * Constructeur de la classe, initialisant les variables
	 */
	function __construct() {
		$this->hooks = new ObjectList();
	}
	
	/*
	 * Methode ajoutant un texte a une ancre
	 */
	function add ($hookname, $text) {
		//Si l'accroche n'existe pas on la cree
		if (!isset($this->hooks[$hookname]))
		{
			$this->hooks[$hookname] = new Hook($hookname);
   			//Debug::display("[HookManager] Creating Hook : ".$hookname);
		}
		//On ajoute le texte a l'accroche
		$this->hooks[$hookname]->addText ($text);
	}
	
	/*
	 * Méthode ajoutant une page a une ancre
	 */
	function addView ($hookname, $model, $template)
	{
		//Si l'accroche n'existe pas on la cree
		if (!isset($this->hooks[$hookname]))
		{
			$this->hooks[$hookname] = new Hook($hookname);
   			//Debug::display("[HookManager] Creating Hook : ".$hookname);
		}
		//On ajoute le texte a l'accroche
		$this->hooks[$hookname]->addView ($model, $template);
	}
	
	/*
	 * Methode retournant le tableau de texte d'une accroche
	 */
	function returnTextArray ($hookname) {
		if (isset($this->hooks[$hookname])) {
			return $this->hooks[$hookname]->returnText();
        }
	}
	
	/**
     * Methode retournant le texte d'une ancre au format string
     */
	function fetch ($hookname) {
        $textLines = "";

		if (isset($this->hooks[$hookname]) && (count($this->hooks[$hookname]->returnText())>0))
        {
	  		Debug::display("[HookManager] Display Hook : " . $hookname);
			foreach ($this->hooks[$hookname]->returnText() as $textLine)
			{
				$textLines .= $textLine . "\n";
			}
			foreach ($this->hooks[$hookname]->returnViews() as $v )
			{
				$textLines .= $v['model']->fetch($v['template']);
			}
		}
        else
        {
	  		Debug::display("[HookManager] Display Hook : " . $hookname . " <em>(empty)</em>");
		}
		return "jon".$textLines;
    }
	
	/*
	 * Methode affichant le texte present dans l'accroche
	 */
	function display ($hookname)
	{
		if (isset($this->hooks[$hookname]) )
		{
			foreach ($this->hooks[$hookname]->returnText() as $textLine )
			{
				echo $textLine . "\n";
			}
			foreach ($this->hooks[$hookname]->returnViews() as $v )
			{
				$v['model']->display($v['template']);
			}
		}
	}
}

//Classe des ancres
class Hook
{
	protected $name; //Propriete contenant le nom de l'accroche
	protected $text; //Propriete contenant le tableau des textes de l'accroche
	protected $views;
	
	//Constructeur de la classe, assignation de la variable $name
	//Initialisation de la variable text comme tableau
	function __construct($name) {
		$this->name = $name;
		$this->text = array();
		$this->views = array();
	}
	
	//Ajout d'un texte a l'accroche
	function addText ($text) {
		array_push($this->text, $text);
	}
	
	function addView($model, $template)
	{
		$this->views[] = array("model"=> $model, "template" => $template);
	}

    //Retourne le tableau de textes de l'accroche
	function returnText () {
		return $this->text;
	}
	
	function returnViews()
	{
		return $this->views;
	}
}

?>
