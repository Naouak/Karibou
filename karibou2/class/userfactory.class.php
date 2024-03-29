<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2009 Rémy Sanchez <remy.sanchez@hyperthese.net>
 * @copyright 2009 Pierre Ducroquet <pinaraf@pinaraf.info>
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
	 * @var CurrentUser
	 */
	protected $currentUser=FALSE;

	/**
	 * @var UserList
	 */
	protected $userList;

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
	 * @var UserFactory
	 */
	private static $instance = null;

	/**
	 * Private constructore because it is a singleton now
	 */
	private function __construct()
	{
		$this->userList = new UserList();

		$this->usersArrayById = array();
		$this->usersArrayByLogin = array();

		$this->whereArray = array();
		$this->whereUserIdArray = array();
		$this->whereUserNameArray = array();
		$this->searchFromClub = false;
	}

	public static function instance() {
		if(self::$instance === null) {
			self::$instance = new UserFactory();
		}

		return self::$instance;
	}

	/**
	 * @return CurrentUser
	 */

	function getCurrentUser()
	{
		// si il y a un utilisateur loggé
		if ( isset($_SESSION['currentUser']) )
		{
			if($this->currentUser === FALSE)
			{
				$this->currentUser = unserialize($_SESSION['currentUser']);
			}
		}
		else
		{
			$this->currentUser = new CurrentUser();
			$_SESSION['currentUser'] = serialize($this->currentUser);
		}
		return $this->currentUser;
	}

	/**
	 * @return CurrentUser
	 */
	function setCurrentUser($userID, $create = false)
	{
		$this->currentUser = new CurrentUser();
		if( $this->currentUser->update(Database::instance(), $userID, $create) )
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
			$stmt = Database::instance()->prepare($qry);
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
			$stmt = Database::instance()->prepare($qry);
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
				$tab["picture"] = "/themes/karibou2/images/0.jpg";
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
			$where .= ' u.id IN (' . implode(", ", $this->whereUserIdArray) .')';
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

		// Pre-query the groups, it's a small optimization, but it can seriously reduce the number of queries if it's done once and for all
		$groups = array();
		if(count($this->whereUserIdArray) > 0)
		{
			$groupsQuery = "
				SELECT
				g.*,
				gu.role,
				gu.user_id
				FROM
				".$a.".group_user gu,
				".$a.".groups g
				WHERE
				g.id=gu.group_id AND
				gu.user_id IN (" . implode(", ", $this->whereUserIdArray) . ")
				ORDER BY
				gu.user_id
				";

			$currentUserId = 0;
			$currentGroups = array();
			foreach (Database::instance()->query($groupsQuery) as $groupTab)
			{
				if ($groupTab["user_id"] != $currentUserId) 
				{
					if ($currentUserId > 0)
						$groups[$currentUserId] = $currentGroups;
					$currentGroups = array();
					$currentUserId = $groupTab["user_id"];
				}
				$group = new Group($groupTab);
				$group->role = $groupTab["role"];
				$currentGroups[] = $group;
			}
			$groups[$currentUserId] = $currentGroups;
		}
		foreach( Database::instance()->query($qry.$where) as $tab )
		{
			if (array_key_exists($tab["id"], $groups))
				$tab["groups"] = $groups[$tab["id"]];
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
			$this->userList[] = $user;
		}
	}

	public function getGroupsfromId ($id) {
		$a = $GLOBALS['config']['bdd']['frameworkdb'];

		$stmt = Database::instance()->prepare("SELECT * FROM ".$a.".groups WHERE id=:id");
		$stmt->bindValue(":id",$id);
		try {
			$stmt->execute();
			if($row = $stmt->fetch()) {
				return new Group($row);
			}
			return null;
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}
	}

	public function getUsersFromGroup (Group $group) {
		$a = $GLOBALS['config']['bdd']['frameworkdb'];

		$stmt = Database::instance()->prepare("SELECT user_id FROM ".$a.".group_user WHERE group_id=:group_id");
		$stmt->bindValue(":group_id", $group->getId());

		$result = array();
		try {
			$stmt->execute();
			while ($row = $stmt->fetch()) {
				$result[] = $this->prepareUserFromId($row['user_id']);
			}
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}
		return $result;
	}

	/**
	 * Return the groups an user belongs to.
	 *
	 * @return array of Group objects
	 */
	function getGroupsFromUser ($user) {
		return $user->getGroups();
	}

	/**
	 * Returns the groups an user belongs to, and their parents
	 *
	 * @return GroupList
	 */
	function getAllGroupsFromUser ($user) {
		return $user->getAllGroups(Database::instance());
	}
}
