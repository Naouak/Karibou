<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 * @copyright 2009 Pierre Ducroquet <pinaraf@gmail.com>
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
 * la methode build() des modÃ¨les qui font appel Ã 
 *
 * @package framework
 */
class HookManager
{
	private $hooks; // Propriété contenant les accroches de page.

	/*
	 * Constructeur de la classe, initialisant les variables
	 */
	function __construct() {
		$this->hooks = new ObjectList();
	}

	public function hasHook ($name) {
		return isset($this->hooks[$name]);
	}

	/*
	 * MÃ©thode ajoutant une page a une ancre
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
	 * Methode affichant le texte present dans l'accroche
	 */
	function display ($hookname)
	{
		if (isset($this->hooks[$hookname]) )
		{
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
	protected $views;

	//Constructeur de la classe, assignation de la variable $name
	function __construct($name) {
		$this->name = $name;
		$this->views = array();
	}

	function addView($model, $template)
	{
		$this->views[] = array("model"=> $model, "template" => $template);
	}

	function returnViews()
	{
		return $this->views;
	}
}

?>
