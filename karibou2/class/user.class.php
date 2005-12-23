<?php
/**
 * @copyright 2003 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2003 Pierre Laden <pladen@elv.enic.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

require_once dirname(__FILE__).'/baseurl.class.php';

/**
 * @package framework
 */
class User
{
	/**
	 * @var Int
	 */
	protected $id=0;

	/**
	 * @var String
	 */
	protected $login;

	/**
	 * @var String
	 */
	protected $lastname;

	/**
	 * @var String
	 */
	protected $firstname;

	/**
	 * @var String
	 */
	protected $surname;

	/**
	 * @var String
	 */
	protected $email;

	/**
	 * @var Array
	 */
	protected $groups = FALSE;
	protected $grouptree = FALSE;
	protected $grouptreeadmin = FALSE;

	/**
	 * @param Int $id
	 * @param String $login
	 * @param String $lastname
	 * @param String $firstname
	 * @param String $surname
	 * @param String $email
	 * @param String $picture
	 */	
    function __construct($tab)
    {
    	if( is_array($tab) )
    	{
			$this->setFromTab($tab);
	    }
	}
	
	public function setFromTab($tab)
	{
    	if( is_array($tab) )
    	{
			if(isset($tab['id'])) $this->id = $tab['id'];
			if(isset($tab['login'])) $this->login     = $tab['login'];
			if(isset($tab['lastname'])) $this->lastname  = $tab['lastname'];
			if(isset($tab['firstname'])) $this->firstname = $tab['firstname'];
			if(isset($tab['surname'])) $this->surname   = $tab['surname'];
			if(isset($tab['email'])) $this->email     = $tab['email'];
			if(isset($tab['picture'])) $this->picture     = $tab['picture'];
	    }
	}
	
    /**
     * @return String
     */
    function getLastname()
    {
    	return $this->lastname;
    }
    
    /**
     * @return String
     */
    function getFirstname()
    {
    	return $this->firstname;
    }
    
    /**
     * @return String
     */
    function getPicturePath()
    {
	   if (isset($this->picture))
	   {
			return $this->picture;
		}
		else
		{
			return "/themes/default/images/0.jpg";
		}
    }
    
    /**
     * @return String
     */
    function afficheUser($debug = FALSE)
    {
        /*
         * on renvoie le Prenom et Nom de l'utilisateur
         */
       	return $this->firstname . " ". $this->lastname;
    }
    
    /**
     * @return String
     */
    function getSurname($debug = FALSE)
    {
        /*
         * on renvoie le Surnom de l'utilisateur
         */

        if ($debug) {
            return print_r($this, TRUE);
        } else {
            return $this->surname;
        }
    }
    

    /**
     * Retourne l id du user instancié
     * @return Int
     */
    function getID()
    {
        return $this->id;
    }

    /**
     * Retourne l id du user instancié
     * @return String
     */
    function getLogin()
    {
        return $this->login;
    }
    

    /**
     * Retourne l email du user instancié
     * @return String
     */
    function getEmail()
    {
        return $this->email;
    }
    

    /**
     * @return String
     */
    function getUserLink()
    {
		if ($this->surname == '')
		{
			if ($this->firstname != '' || $this->lastname != '')
			{
				$pseudo = $this->firstname." ".$this->lastname;
			}
			else
			{
				$pseudo = $this->login;
			}
		}
		else
		{
			$pseudo = $this->surname;
		}
		
		$pseudo = htmlspecialchars($pseudo,ENT_QUOTES);
		
		return $pseudo;
    }
    
    /**
	 * @param PDO $db
	 */
	function getGroups(PDO $db)
	{
		if( $this->groups !== FALSE )
		{
			return $this->groups;
		}
		
		$annudb = $GLOBALS['config']['bdd']['annuairedb'];
		if( empty($this->id) )
		{
			$qry = "SELECT 
					g.* ,
					gu.role
				FROM
					".$annudb.".group_user gu,
					".$annudb.".groups g,
					".$annudb.".users u
				WHERE
					u.id=gu.user_id AND
					g.id=gu.group_id AND
					u.login='".$this->login."';" ;
		}
		else
		{
			$qry = "SELECT 
					g.* ,
					gu.role
				FROM
					".$annudb.".group_user gu,
					".$annudb.".groups g
				WHERE
					g.id=gu.group_id AND
					gu.user_id='".$this->id."';" ;
		}
		try
		{
			$stmt = $db->query($qry);
		}
		catch( PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		$this->groups = array();
		while( $tab = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$group = new Group($tab);
			$group->role = $tab['role'];
			$this->groups[]	= $group;
		}
		unset($stmt);
	}
	
	function getGroupTreeWhereArray()
	{
		if( $this->groups === FALSE )
		{
			Debug::kill("Need to fetch Groups first, use getGroups function before calling getGroupTreeWhereArray");
		}
		$where = array();
		foreach( $this->groups as $g)
		{
			$where[] = " ((`left` <= ".$g->getLeft().") AND (`right` >= ".$g->getRight().")) ";
		}
		return $where;
	}
	
	function getGroupTreeQuery($cols = "id")
	{
		$annudb = $GLOBALS['config']['bdd']['annuairedb'];

		$where = $this->getGroupTreeWhereArray();

		$where_clause = " WHERE (`left` < 0) ";
		foreach($where as $w)
		{
			$where_clause .= " OR ";
			$where_clause .= $w;
		}
		
		$qry_groups = "SELECT ".$cols." FROM ".$annudb.".groups";
		$qry_groups .= $where_clause;
		
		return $qry_groups;
	}

	function getAllGroups(PDO $db)
	{
		if( $this->grouptree !== FALSE )
		{
			return $this->grouptree;
		}
		
		$this->getGroups($db);
		$qry = $this->getGroupTreeQuery("*");
		
		try
		{
			$stmt = $db->query($qry) ;
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		while( $tab = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$this->grouptree[] = new Group($tab);
		}
		unset($stmt);
		return $this->grouptree;
	}
	
	function isInGroup(PDO $db, $groupid)
	{
		$this->getAllGroups($db);
		if (is_array($this->grouptree))
		{
			foreach($this->grouptree as $group)
			{
				if ($group->getId() == $groupid)
				{
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	function getGroupTreeWhereAdminArray()
	{
		if( $this->groups === FALSE )
		{
			Debug::kill("Need to fetch Groups first, use getGroups function before calling getGroupTreeWhereArray");
		}
		$where = array();
		foreach( $this->groups as $g)
		{
			if( $g->role == "admin" )
			{
				$where[] = " ((`left` >= ".$g->getLeft().") AND (`right` <= ".$g->getRight().")) ";
			}
		}
		return $where;
	}
	function getGroupTreeAdminQuery($cols = "id")
	{
		$annudb = $GLOBALS['config']['bdd']['annuairedb'];

		$where = $this->getGroupTreeWhereAdminArray();

		$where_clause = " WHERE (`left` < 0) ";
		foreach($where as $w)
		{
			$where_clause .= " OR ";
			$where_clause .= $w;
		}
		
		$qry_groups = "SELECT ".$cols." FROM ".$annudb.".groups";
		$qry_groups .= $where_clause;
		
		return $qry_groups;
	}
	
	function getAllAdminGroups(PDO $db)
	{
		if( $this->grouptreeadmin !== FALSE )
		{
			return $this->grouptreeadmin;
		}
		
		$this->getGroups($db);
		$qry = $this->getGroupTreeAdminQuery("*");
		
		Debug::display($qry);
		
		try
		{
			$stmt = $db->query($qry) ;
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage()) ;
		}
		$this->grouptreeadmin = array();
		while( $tab = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$this->grouptreeadmin[] = new Group($tab) ;
		}
		unset($stmt);
		return $this->grouptreeadmin ;
	}

}

?>