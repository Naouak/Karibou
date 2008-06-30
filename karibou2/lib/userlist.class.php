<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package lib
 **/

require_once dirname(__FILE__).'/objectlist.class.php';

/**
 * Une liste d'utilisateurs avec fonctions de tris et filtres
 *
 * @package lib
 */
class UserList extends ObjectList
{
	/**
	 * @var Array $usersById tableau d'utilisateurs ordonné par ID
	 */
	public $users;
	
	/**
	 * @var String $searchStringForFilter variable temporaire pour le filtre
	 */
	protected $searchStringForFilter;
	
	/**
	 * @var Int $promoIdForFilter variable temporaire pour le filtre
	 */
	protected $promoIdForFilter;
	
	function __construct($data = false)
	{
		parent::__construct($data);
		$this->users = array();
		if( is_array($data) )
		{
			reset($data);
			foreach($data as $user)
			{
				$this->users[$user->getID()] = $user;
			}
		}
	}
	
	/**
	 * Ajouter un utilisateur dans la liste
	 *
	 * Stocke aussi les utilisateurs suivant leur ID, pour les
	 * récupérer directement
	 *
	 * @param UtilisateurSimple $user
	 */
	function add($user)
	{
		parent::add($user);
		$this->users[$user->getID()] = $user;
	}
	
	/**
	 * Pour récupérer l'objet utilisateur en fonction de son Id
	 *
	 * @return UtilisateurSimple
	 */
 	function getUser($id)
	{
		if( isset($this->users[$id] ) )
		{
			return $this->users[$id];
		}
		return $this->users[0];
	}
	
	/*
	 * Les filtres
	 */
	
	/**
	 * redefinition de la fonction filter pour retourner un UserList
	 *
	 * @var String $function nom de la fonction de filtre
	 */
	function filter($function)
	{
		$filtered_tab = array_filter($this->data, $function);
		return new UserList($filtered_tab);
	}
		
	function getBirthdayUsers()
	{
	}

	/*	
	 * Les tris
	 */
	function sortByAges()
	{
	}
	
	function sortByLastname()
	{
		return $this->sort( array($this, "sortByLastnameFunction") );
	}
	
	function sortByFirstname()
	{
		return $this->sort( array($this, "sortByFirstnameFunction") );
	}
	
	/*
	 * Les fonctions pour le tri
	 */
	
	function sortByLastnameFunction($user1, $user2)
	{
		return strcmp($user1->getLastame(), $user2->getLastname());
	}

	function sortByFirstnameFunction($user1, $user2)
	{
		return strcmp($user1->getFirstname(), $user2->getFirstname());
	}
}

?>
