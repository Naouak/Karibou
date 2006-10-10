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
 * Classe de gestion des messages
 * @package framework
 */
Class KFormField
{
	public $name;
	public $type;
	public $label;
	public $defaultvalue;
	public $uservalue;
	
	//Content est utilisé pour stocker les valeurs des champs : select, options, checkbox...
	public $content;
	
	//Utilisé pour l'affichage de fieldset
	public $fieldgroupstart		= FALSE;
	public $fieldgroupstop		= FALSE;
	public $fieldgroupoptions	= FALSE;
	
	public $form;
	
	public $rules = array();

	/**
	 * Constructeur du champ, à partir de paramètres string ou array
	 */
	public function __construct($form, $array, $name = FALSE, $defaultvalue = FALSE)
	{
		$this->form			= $form;
		if ($array !== FALSE && is_array($array))
		{
			$this->name = $array['name'];
			
			if (isset($array['defaultvalue']))
				$this->defaultvalue = $array['defaultvalue'];

			if (isset($array['type']))
				$this->type = $array['type'];

			if (isset($array['label']))
				$this->label = $array['label'];

			if (isset($array['content']))
				$this->content = $array['content'];
		}
		else
		{
			$this->name			= $name;
			$this->defaultvalue	= $defaultvalue;
		}
	}
	
	//Retourne la valeur du champ
	public function getCurrentValue()
	{
		if (isset($this->uservalue))
		{
			return $this->uservalue;
		}
		elseif (isset($this->defaultvalue) && $this->defaultvalue !== FALSE)
		{
			return $this->defaultvalue;
		}
		else
		{
			return "";
		}
	}
	
	//Ajoute une règle de validation au champ
	public function addRule($rulename, $errormessage, $options)
	{
		//Ajout de la règle uniquement si elle n'existe pas encore
		// ? -> Cas du changement de langue au moment de la validation?
		$exists = FALSE;
		foreach($this->rules as $rule)
		{
			if ($rule['type'] == $rulename)
			{
				if (is_array($rule['options']) && is_array($options) && count(array_diff_assoc($rule['options'], $options))==0)
					$exists = TRUE;
				elseif (!is_array($rule['options']) && !is_array($options) && $rule['options'] == $options)
					$exists = TRUE;
			}
		}
		
		if (!$exists)
			$this->rules[] = array('type' => $rulename, 'errormessage' => $errormessage, 'options' => $options);
	}
}

?>