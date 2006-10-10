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
Class KFormFactory
{

	static protected $currentformname = FALSE;
	protected $fields;

	public function __construct()
	{
		
	}
	
	public function getForm($name)
	{
		if (isset($_SESSION['kforms'][$name]))
		{
			return $_SESSION['kforms'][$name];
		}
		else
		{
			return FALSE;
		}
	}
	
	//Ouverture du formulaire courant
	public function open($formname)
	{
		if (KFormFactory::$currentformname !== FALSE)
		{
			//Un formulaire est déjà ouvert
			Debug::kill('Form already open ('. KFormFactory::$currentform .')');
		}
		else
		{
		
			if (isset($_SESSION['kforms'][$formname]) && (!isset($_SESSION['kforms'][$formname]->persist) || $_SESSION['kforms'][$formname]->persist == TRUE))
			{
				//Un formulaire est déjà présent en session, on le reprend s'il est persistant
				//La variable persist détermine s'il faut écraser le formulaire ou non
				$_SESSION['kforms'][$formname]->persist = FALSE;
			}
			else
			{
				if (isset($_SESSION['kforms'][$formname]) && $_SESSION['kforms'][$formname]->persist == FALSE)
				{
					echo 'Ecrasement du formulaire '.$formname;
				}
				//Le formulaire n'est pas présent en session, on le créé
				$_SESSION['kforms'][$formname] = new KForm($formname);
			}
			KFormFactory::$currentformname = $formname;
			return $_SESSION['kforms'][$formname];	
		}
	}
	
	static public function getCurrentForm()
	{
		if (KFormFactory::$currentformname !== FALSE && $_SESSION['kforms'][KFormFactory::$currentformname])
		{
			return $_SESSION['kforms'][KFormFactory::$currentformname];
		}
		else
		{
			return FALSE;
		}
	}
	
	//Cloture du formulaire courant
	public function close($formname)
	{
		//Réinitialiser les erreurs?
		
		
		if (!isset(KFormFactory::$currentformname))
		{
			//Un formulaire est déjà ouvert
			Debug::kill('Form not opened ('. $formname .')');
		}
		else
		{
			KFormFactory::$currentformname = FALSE;
		}
	}
}

?>