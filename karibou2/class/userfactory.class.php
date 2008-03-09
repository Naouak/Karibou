<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

require_once dirname(__FILE__).'/user.class.php';
require_once KARIBOU_LIB_DIR.'/userlist.class.php';
require_once KARIBOU_LIB_DIR.'/grouplist.class.php';

/**
 * Interface cliente de la base Annuaire
 *
 * Cette classe permet l'accés aux informations de l'annuaire
 * 
 * @package framework
 */
class UserFactory
{	
	/**
	 * @var PDO
	 */
	protected $db;
	
	/**
	 * @var UtilisateurCourant
	 */
	protected $currentUser=FALSE;
	
	/**
	 * @var UserList
	 */
 	protected $userList;
	
	/**
	 * @var ObjectList
	 */
	protected $promoList;

	/**
	 * @var ObjectList
	 */
	protected $clubList;

	/**
	 * @var array
	 */
	protected $usersArrayById;
    	/**
	 * @var array
	 */
	protected $usersArrayByLogin;
    
	/**
	 * @var array
	 */
	protected $whereArray;
	
	/**
	 * @var array
	 */
	protected $whereUserIdArray;
	
	/**
	 * @var array
	 */
	protected $whereUserNameArray;
	
	/**
	 * @param PDO $p_db
	 */
	public function __construct(PDO $p_db)
	{
		$this->db = $p_db;
		$this->userList = new UserList();
		$this->promoList = false;
		$this->clubList = false;
	
		$this->usersArrayById = array();
		$this->usersArrayByLogin = array();
		
		$this->whereArray = array();
		$this->whereUserIdArray = array();
		$this->whereUserNameArray = array();
		$this->searchFromClub = false;
	}
	
	/**
	 * @return UtilisateurCourant
	 */
	
	function getCurrentUser($reFetch = false)
	{
		// si il y a un utilisateur loggé
		if ( session_is_registered('currentUser') )
		{
			// on récupère en session
			if($reFetch)
			{
				$this->currentUser = new CurrentUser();
				$this->currentUser->update($this->db, $this->currentUser->getLogin());
				$_SESSION['currentUser'] = serialize($this->currentUser);
			}
			else
			{
				if($this->currentUser === FALSE)
				{
					$this->currentUser = unserialize($_SESSION['currentUser']);
				}
			}
		}
		else
		{
			session_register('currentUser');
			$this->currentUser = new CurrentUser();
			$_SESSION['currentUser'] = serialize($this->currentUser);
		}
		return $this->currentUser;
	}
	
	/**
	 * @return UtilisateurCourant
	 */
	function setCurrentUser($userID, $create = false)
	{
		$this->currentUser = new CurrentUser();
		if( $this->currentUser->update($this->db, $userID, $create) )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	function saveCurrentUser()
	{
		$currentUser = $this->getCurrentUser();
		$_SESSION['currentUser'] = serialize($currentUser);
	}
	
	/**
	 * renvoie une liste d'objets Group
	 *
	 * @return ObjectList liste des utilisateurs du groupe
	 */

	function getGroups()
	{
		if(!isset($this->groupList))
		{
			$this->groupList = new GroupList();
		}
		else
		{
			return $this->groupList;
		}
		$qry = "SELECT * FROM ".$GLOBALS['config']['bdd']["frameworkdb"].".groups ORDER BY `left` ASC";
		try
		{
			$stmt = $this->db->prepare($qry);
			$stmt->execute();
		}
		catch( PDOException $e )
		{
			Debug::kill($e->getMessage());
		}
		while($tab = $stmt->fetch())
		{
			$group = new Group( $tab );
			$this->groupList[] = $group;
		}
		return $this->groupList;
	}
	
	/**
	 * Pour récupérer les utilisateurs sur une recherche
	 *
	 * Recherche les étudiants qui correspondent au critère de recherche
	 * parmi :
	 *  - nomEtudiant
	 *  - prenomEtudiant
	 *  - Surnom
	 *
	 * @param string $queryString 
	 * @return array
	 */	
	function getUsersFromSearch($queryString)
	{
		$q = addslashes($queryString);
		$a = $GLOBALS['config']['bdd']["frameworkdb"];
		$qry = "SELECT * FROM
					".$a.".users u LEFT OUTER JOIN ".$a.".profile p
				ON u.profile_id=p.id
				WHERE
					u.login LIKE '%".$q."%' OR
					p.firstname LIKE '%".$q."%' OR
					p.lastname LIKE '%".$q."%' OR
					p.surname LIKE '%".$q."%'";

		$userList = array();
		try
		{
			$stmt = $this->db->prepare($qry);
			$stmt->execute();
		}
		catch( PDOException $e )
		{
			Debug::kill($e->getMessage());
		}
		while($tab = $stmt->fetch())
		{
		
			if( is_file(KARIBOU_PUB_DIR.'/profile_pictures/'.$tab["id"].".jpg") )
			{
				$tab["picture"] = "/pub/profile_pictures/".$tab["id"].".jpg";
			}
			else
			{
				$tab["picture"] = "/themes/default/images/0.jpg";
			}
		
			$user = new User($tab);
			
			$userList[] = $user;
		}
		
		return $userList;
	}
		
	
	function prepareUserFromId($id)
	{
		if( !in_array($id, $this->whereUserIdArray) )
		{
			$this->whereUserIdArray[] = $id;
			$this->usersArrayById[$id] = new User( array('id' => $id) );
		}
		return $this->usersArrayById[$id];
	}
	
	function prepareUserFromLogin($username)
	{
		if( !in_array($username, $this->whereUserNameArray) )
		{
			$this->whereUserNameArray[] = $username;
			$this->usersArrayByLogin[$username] = new User( array('login' => $username) );
		}
		return $this->usersArrayByLogin[$username];
	}	
	/**
	 * Après préparation, va rechercher les utilisateurs en BDD
	 */
	function setUserList()
	{
		if( (count($this->whereArray) == 0) 
			and (count($this->whereUserIdArray) == 0)
			and (count($this->whereUserNameArray) == 0)
			) return;
		$a = $GLOBALS['config']['bdd']["frameworkdb"];
		$qry = "SELECT
					u.id,
					LOWER(u.login) login,
					p.firstname,
					p.lastname,
					p.surname,
					u.profile_id
				FROM
					".$a.".users u LEFT OUTER JOIN ".$a.".profile p ON u.profile_id=p.id";
			
		$where = " WHERE";
		
		$first = true;
		foreach($this->whereArray as $w)
		{
			if(!$first) $where .= " OR";
			$where .= " ".$w;
			$first = false;
		}
		
		if(count($this->whereUserIdArray) > 0)
		{
			if(!$first) $where .= ' OR';
			$where .= ' u.id IN (';		
			$where .= '0';

			foreach($this->whereUserIdArray as $userId)
			{
				$where .= ', '.$userId;
			}
			$where .= ")";
			$first = false;
		}

		if(count($this->whereUserNameArray) > 0)
		{
			if(!$first) $where .= ' OR';
			$where .= ' LOWER(u.login) IN (';		
			$where .= '\'0\'';

			foreach($this->whereUserNameArray as $username)
			{
				$where .= ', \''.strtolower($username).'\'';
			}
			$where .= ")";
			$first = false;
		}
		
		foreach( $this->db->query($qry.$where) as $tab )
		{
			if( isset($this->usersArrayById[$tab["id"]]) )
			{
				$user = $this->usersArrayById[$tab["id"]];
				$user->setFromTab($tab);
			}
			
			if( isset($this->usersArrayByLogin[$tab["login"]]) )
			{
				$user = $this->usersArrayByLogin[$tab["login"]];
				$user->setFromTab($tab);
			}
			
			if( !isset($user) )
			{
				$user = new UserSimple();
				$user->setFromTab($tab);
			}

			$this->userList[] = $user;
		}
	}
	
	/**
	 * Renvoie la liste des étudiants récupérés en base de données
	 *
	 * @return UserList
	 */
	function getUserList()
	{
		return $this->userList;
	}
	
	/**
	 * Return the groups an user belongs to.
	 *
	 * @return array of Group objects
	 */
	function getGroupsFromUser ($user) {
		return $user->getGroups($this->db);
	}
}

?>
