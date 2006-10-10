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
Class KForm
{

	/**
	 * Tableaux
	 */
	protected $fields;
	protected $rules;
	
	/**
	 * Variables pour l'affichage
	 */
	protected $method;
	protected $action;
	protected $name;
	
	public $persist;

	public function __construct($name)
	{
		$this->name = $name;
	}
	
	/**
	 * Initialisation des options du formulaire (pour affichage complet) : action & method
	 */
	public function setOptions($options)
	{
		if (isset($options['action']))
			$this->action = $options['action'];
		else
			$this->action = '';

		if (isset($options['method']))
			$this->method = $options['method'];
		else
			$this->method = 'post';
	}
	
	/**
	 * Validation des champs du formulaire
	 */
	public function validate($post)
	{
		//Intégration des variables POST
		/*
		foreach($post as $key => $value)
		{
			if (isset($this->fields[$key]))
			{
				$this->fields[$key]->uservalue = $value;
			}
		}
		*/
		
		foreach ($this->fields as $key => &$field)
		{
			if (isset($post[$key]))
			{
				//On assigne la valeur postée dans le formulaire
				$field->uservalue = $post[$key];
			}
			else
			{
				//On réinitialise la valeur à '' (vide) : nécessaire pour les checkboxes qui ne sont pas envoyées en post lorsque déselectionnées
				$field->uservalue = '';
			}
		}
		
		
		$valid = TRUE;
		
		//Validation du formulaire
		if (isset($this->fields) && count($this->fields) > 0)
		{
			foreach($this->fields as &$field)
			{
				//On réinitialise la validation du champ
				$field->valid = TRUE;
				
				//On vérifie les règles du champ s'il y en a
				if (count($field->rules) > 0 && ($field->type != 'submit'))
				{
					foreach ($field->rules as &$rule)
					{
						//Cas d'une validation simple (NotEmpty)
						switch ($rule['type'])
						{
							case 'NotEmpty':
								if ($field->uservalue != '')
									$rule['valid'] = TRUE;
								else
									$rule['valid'] = FALSE;
							break;
							case 'EqualTo':
								if ($field->uservalue == $this->fields[$rule['options']['comparedfield']]->uservalue)
									$rule['valid'] = TRUE;
								else
									$rule['valid'] = FALSE;
							break;
							default:
								//Debug::kill('Error : "'.$rule['type'].'" not defined');
								print ("\nError : '".$rule['type']."' not defined\n");
								$field->valid = FALSE;
							break;
						}
						
						//Si au moins un champ n'est pas valide, le formulaire est en erreur
						if ($rule['valid'] !== TRUE)
						{
							$field->valid = FALSE;
							$valid = FALSE;
						}
					}
				}
			}
		}
		else
		{
			//Si aucun champ, le formulaire n'a pas à être validé
			$valid = TRUE;
		}
		
		//Variable de persistance initialisée car le formulaire ne doit pas être réinitialisé à son prochain chargement
		$this->persist = true;
		
		return $valid;
	}
	
	/***************************************************
	 * Fonctions relatives aux champs (sauf affichage) *
	 ***************************************************/
	
	/**
	 * Ajout d'un champ au formulaire
	 */
	public function addField($name, $defaultvalue = FALSE)
	{
		if (!isset($this->fields[$name]))
		{
			//Si le champ n'existe pas dans le formulaire, on l'aujoute
			$this->fields[$name] = new KFormField(&$this, FALSE, $name, $defaultvalue);
		}
		else
		{
			//S'il existe on ne fait rien
		}
	}
	
	/**
	 * Ajout d'un champ au formulaire (via un tableau)
	 */
	public function addFieldFromArray($array)
	{
		if (isset($array['name']))
		{
			if (!isset($this->fields[$array['name']]))
			{
				//Si le champ n'existe pas dans le formulaire, on l'aujoute
				$this->fields[$array['name']] = new KFormField(&$this, $array);
			}
			else
			{
				//S'il existe on ne fait rien
			}
		}
	}
	
	/**
	 * Retourne la valeur du champ demandé
	 */
	public function getFieldValue($fieldname)
	{
		if (isset($this->fields[$fieldname]))
		{
			return $this->fields[$fieldname]->getCurrentValue();
		}
		else
		{
			return '';
		}
	}
	
	/**
	 * Ajout d'un règle de validation au champ
	 */
	public function addRuleToField($fieldname, $rule, $errormessage, $options = FALSE)
	{
		$this->fields[$fieldname]->addRule($rule, $errormessage, $options);
	}
	
	/**
	 * Vérifie si un champ a été validé
	 */
	public function fieldValidate($fieldname)
	{
		//var_dump($this);
		//die;
		//var_dump($this->fields);
		//die;
		if (isset($this->fields[$fieldname]->valid))
		{
			return $this->fields[$fieldname]->valid;
		}
		else
		{
			return TRUE;
		}
	}
	
	//Retourne les erreurs du champ
	public function getFieldErrors($fieldname)
	{
		$array = array();
		if (isset($this->fields[$fieldname]) && count($this->fields[$fieldname]) > 0)
		{
			if (isset($this->fields[$fieldname]->rules) && count($this->fields[$fieldname]->rules) > 0)
			{
				foreach ($this->fields[$fieldname]->rules as $rule)
				{
					if (isset($rule['valid']) && ($rule['valid'] !== TRUE))
					{
						$array[] = $rule['errormessage'];
					}
					else
					{
						//La validation de la règle n'est pas présente ou la règle est vérifiée
					}
				}
			}
		}
		return $array;
	}
	
	public function addFieldGroup($fieldnamestart, $fieldnamestop, $legend)
	{
		if (isset($this->fields[$fieldnamestart]))
		{
			$this->fields[$fieldnamestart]->fieldgroupstart = TRUE;
			$this->fields[$fieldnamestart]->fieldgroupoptions = array('legend'=>$legend);
		}
		
		if (isset($this->fields[$fieldnamestop]))
		{
			$this->fields[$fieldnamestop]->fieldgroupstop = TRUE;
		}
	}
	
	/******************************
	 * Méthode relative au bouton *
	 ******************************/
	public function addButton($options = array())
	{
		$array = array_merge(array('name' => 'button', 'type' => 'submit'), $options);
		$this->addFieldFromArray($array);
	}
	
	/************************************
	 * Méthodes relatives à l'affichage *
	 ************************************/
	
	/**
	 * Affiche le formulaire complet
	 */
	public function displayForm()
	{
?>
<form action="<?=$this->action;?>" method="<?=$this->method;?>">
<?
		foreach($this->fields as $field)
		{

			if ($field->fieldgroupstart)
			{
?>
		<fieldset>
			<legend><?=$field->fieldgroupoptions['legend']?></legend>
<?
			}

			$this->displayField($field->name);

			if ($field->fieldgroupstop)
			{
?>
		</fieldset>
<?
			}
		}
?>
</form>
<?
	}
	
	/**
	 * Affiche le champ (avec messages d'erreur)
	 */
	public function displayField($fieldname)
	{
		if (isset($this->fields[$fieldname]))
		{
			if ($this->fields[$fieldname]->type == 'submit')
			{
?>

		<input type="submit" name="<?=$this->fields[$fieldname]->name?>" />
<?
			}
			else
			{
				if (!$this->fieldValidate($fieldname))
				{
					$errors = $this->getFieldErrors($fieldname);
					foreach($errors as $error)
					{
						if ($error !== FALSE)
							echo '<div style="color: #a00;">'._($error).'</div>';
					}
				}
				
				/**
				 * textarea
				 */
				if ($this->fields[$fieldname]->type == 'textarea')
				{
?>

		<label for="<?=$fieldname;?>"<?if (!$this->fieldValidate($fieldname)){?> style="color: #a00; font-weight: bold;"<?}?>><?=$this->fields[$fieldname]->label;?> :</label>
		<textarea name="<?=$fieldname;?>"<?if (!$this->fieldValidate($fieldname)){?> style="background-color: #fcc;"<?}?>><?=$this->getFieldValue($fieldname);?></textarea>
<?
				}
				/**
				 * select
				 */
				elseif ($this->fields[$fieldname]->type == 'select')
				{
?>

		<label for="<?=$fieldname;?>"<?if (!$this->fieldValidate($fieldname)){?> style="color: #a00; font-weight: bold;"<?}?>><?=$this->fields[$fieldname]->label;?> :</label>
		<select name="<?=$fieldname;?>"<?if (!$this->fieldValidate($fieldname)){?> style="background-color: #fcc;"<?}?>>
<?
					foreach ($this->fields[$fieldname]->content as $value => $name)
					{
?>
			<option value="<?=$value;?>"<?if($this->getFieldValue($fieldname) == $value){?> selected<?}?>><?=$name?></option>
<?
					}
?>
		</select>
<?
				}
				/**
				 * checkbox
				 */
				elseif ($this->fields[$fieldname]->type == 'checkbox')
				{
?>

		<label for="<?=$fieldname;?>"<?if (!$this->fieldValidate($fieldname)){?> style="color: #a00; font-weight: bold;"<?}?>><?=$this->fields[$fieldname]->label;?> :</label>
		<input type="checkbox" name="<?=$fieldname;?>" <?if($this->getFieldValue($fieldname) == 'on'){?>checked <?}?>/>
<?
				}
				/**
				 * other (text)
				 */
				else
				{
					//Text
?>

		<label for="<?=$fieldname;?>"<?if (!$this->fieldValidate($fieldname)){?> style="color: #a00; font-weight: bold;"<?}?>><?=$this->fields[$fieldname]->label;?> :</label>
		<input type="text" name="<?=$fieldname;?>" value="<?=$this->getFieldValue($fieldname);?>" <?if (!$this->fieldValidate($fieldname)){?>style="background-color: #fcc;"<?}?> />
		
<?
				}
			}
		}
		else
		{
			return '[?'.$fieldname.'?]';
		}
	}
}