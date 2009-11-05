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
	 * @var Int
	 */
	protected $profile_id;

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
			//if(isset($tab['picture'])) $this->picture     = $tab['picture'];
			if(isset($tab['profile_id'])) $this->profile_id     = $tab['profile_id'];
            if(isset($tab['groups'])) $this->groups     = $tab['groups'];
	    }
	}
	
    /**
     * @return String
     */
    function getLastname()
    {
    	if (isset($this->lastname))
    	{
	    	return $this->lastname;
		}
		else
		{
			return FALSE;
		}
    }
    
    /**
     * @return String
     */
    function getFirstname()
    {
    	if (isset($this->firstname))
    	{
	    	return $this->firstname;
		}
		else
		{
			return FALSE;
		}
    }
    
    
    /**
     * @return Int
     */
    function getProfileId()
    {
    	if (isset($this->profile_id) && $this->profile_id !== FALSE)
    	{
    		return $this->profile_id;
    	}
    	else
    	{
    		return FALSE;
    	}
    }
     
    /**
     * @return String
     */
    function getPicturePath()
    {
	   if ($this->getProfileId())
	   {
	   	$picturepath = "/profile_pictures/".$this->getProfileId().".jpg";
			if (is_file (KARIBOU_PUB_DIR.$picturepath))
			{
				return "/pub".$picturepath;
			}
		}
		return "/themes/karibou/images/0.jpg";
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
    function getDisplayName()
    {
		if ( ($this->surname == '') || (isset($GLOBALS['config']['nonickname']) && ($GLOBALS['config']['nonickname'] === TRUE)) )
		{
			if ($this->firstname != '' || $this->lastname != '')
			{
				$displayname = $this->firstname." ".$this->lastname;
			}
			else
			{
				$displayname = $this->login;
			}
		}
		else
		{
			$displayname = $this->surname;
		}

		$displayname = htmlspecialchars($displayname);
		
		return $displayname;
    }
    
    /**
     * $firstname $lastname
     */
    function getFullName()
    {
		if ($this->firstname != '' || $this->lastname != '')
		{
			$fullname = $this->firstname." ".$this->lastname;
		}
		else
		{
			$fullname = $this->login;
		}
		
		$fullname = htmlspecialchars($fullname);
		return $fullname;
		
    }
    
    /**
     * ... temp method replaced by getDisplayName()
     * @return String
     */
    function getUserLink()
    {
		return $this->getDisplayName();
    }
    
    
	/**
	 * @param PDO $db
	 */
	function getGroups()
	{
		$db = Database::instance();
		if( $this->groups !== FALSE )
		{
			return $this->groups;
		}
		
		$annudb = $GLOBALS['config']['bdd']['frameworkdb'];
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
		return $this->groups;
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
		$annudb = $GLOBALS['config']['bdd']['frameworkdb'];

		$where = $this->getGroupTreeWhereArray();

		$where_clause = " WHERE (`left` < 0) ";
		foreach($where as $w)
		{
			$where_clause .= " OR ";
			$where_clause .= $w;
		}
		
		$qry_groups = "SELECT ".$cols." FROM ".$annudb.".groups";
		$qry_groups .= $where_clause;
		
		//Added for usability with GroupList
		$qry_groups .= "  ORDER BY `left` ASC";
		
		return $qry_groups;
	}

	function getAllGroups(PDO $db)
	{
		if( $this->grouptree !== FALSE )
		{
			return $this->grouptree;
		}
		else
		{
			$this->grouptree = new GroupList();
		}
		
		$this->getGroups();
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
		if (is_array($this->grouptree) || is_object($this->grouptree))
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

    function isGlobalAdmin(PDO $db) {
        if ($this->isInGroup($db, $GLOBALS["config"]["admingroup"]))
            return TRUE;
        else
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
		$annudb = $GLOBALS['config']['bdd']['frameworkdb'];

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
		
		$this->getGroups();
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
