<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package lib
 **/

/**
 * Gestion des messages applicatifs provenant des formulaires
 * Cette classe centralise la gestion des messages applicatifs provenant des formulaires
 *
 * @package lib
 */
class FormMessage
{
	public $messages; //Propriete contenant le message

	/*
	 * Gravite des messages
	 */

	const SUCCESS		=	0;
	const INFO			=	1;
	const NOTICE		=	2;
	const WARNING		=	3;
	const FATAL_ERROR	=	4;
	
	/*
	 * Constructeur de la classe, initialisant les variables
	 */
	function __construct() {
		$this->messages = array();
	}

	/*
	 * Methode ajoutant un message
	 */
	function add ($gravity, $message) {
		array_push ($this->messages, array($gravity, $message) ); 
	}

	/*
	 * Methode effacant la variable de session contenant les messages
	 */
	function flush () {
		unset($this->messages);
		$this->unsetSession();
	}
	
	/*
	 * Methode affectant la variable message dans la session
	 */
	function setSession () {
		//Si la variable de session formMessages n'est pas creee, on la definit comme array
		if (!isset($_SESSION["formMessages"])) {
			$_SESSION["formMessages"] = array();
		}

		//S'il y a des messages, on les ajoute a la variable de session
		if ( count($this->messages) > 0 ) {
			foreach($this->messages as $message) {
				array_push ($_SESSION["formMessages"], $message);
			}
		}
	}
	
	/*
	 * Methode retournant la variable de session de gestion des messages
	 */
	function getSession () {
		if (isset($_SESSION["formMessages"])) {
			return $_SESSION["formMessages"];
		} else {
			return FALSE;
		}
	}
	
	/*
	 * Methode vidant la variable de session contenant les messages
	 */
	function unsetSession () {
		unset($_SESSION["formMessages"]);
	}
}
?>