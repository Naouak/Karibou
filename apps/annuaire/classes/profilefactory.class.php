<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/


require_once dirname(__FILE__).'/profile.class.php';

/**
 * Display profile
 * 
 * @package applications
 */

class ProfileFactory
{
	protected $db;
	protected $annudb;
	protected $profile_table;
	
	function __construct(PDO $db, $profile_table = 'profile')
	{
		$this->profile_table = $profile_table ;
		$this->annudb = $GLOBALS['config']['bdd']["frameworkdb"];
		$this->db = $db;
	}
	
	function fetchFromUsername($username)
	{
		$qry = "SELECT p.*
			FROM ".$this->annudb.".users u , ".$this->profile_table." p
			WHERE u.profile_id=p.id
			AND u.login='".$username."'" ;
		try
		{
			$stmt = $this->db->prepare($qry);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		if( $profile = $stmt->fetch(PDO::FETCH_ASSOC) )
		{	
			unset($stmt);
			return new Profile($profile);
		}
		unset($stmt);
	}
	function fetchFromId($profile_id)
	{
		if ($profile_id != "" && $profile_id > 0)
		{
			//Check if _user table exists
			if ($this->profile_table == "profile")
			{
				$qry = "
					SELECT
						p.*
					FROM
						".$this->profile_table." p
					WHERE
						p.id = ".$profile_id."" ;
			}
			else
			{
				$qry = "
					SELECT
						p.*, ".$this->profile_table."_user.user_id AS userid
					FROM
						".$this->profile_table." p
					LEFT JOIN
						".$this->profile_table."_user
						ON
							".$this->profile_table."_user.profile_id = p.id
					WHERE
						p.id = ".$profile_id."";
			}
			
			try
			{
				$stmt = $this->db->prepare($qry);
				$stmt->execute();
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
			if( $profile = $stmt->fetch(PDO::FETCH_ASSOC) )
			{	
				unset($stmt);
				return new Profile($profile);
			}
			unset($stmt);
		}
		return FALSE;
	}
	
	/**
	 * Fetch profiles from multiple ids
	 */
	function fetchFromIds($ids)
	{
		if (count($ids)>0)
		{
			$whereids = "";
			foreach ($ids as $id)
			{
				if ($whereids == "")
				{
					$whereids .= "p.id = '$id'";
				}
				else
				{
					$whereids .= "OR id = '$id'";
				}
			}
			
			$qry = "
				SELECT
					p.*
				FROM
					".$this->profile_table." p
				WHERE 
					$whereids ";
			try
			{
				$stmt = $this->db->prepare($qry);
				$stmt->execute();
				
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				$profiles = array();
				foreach($results as $result)
				{
					$profiles[] = new Profile($result);
				}
				unset($stmt);
				
				return $profiles;
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
			return FALSE;
		}
		else
		{
			return array();
		}
	}

	function fetchAddresses($profile)
	{
		try
		{
			$qry = "SELECT * FROM ".$this->profile_table."_address 
				WHERE profile_id='".$profile->getId()."'";
			$stmt = $this->db->prepare($qry);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessagae());
		}
		$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
		unset($stmt);
		$profile->setAddresses($addresses);
	}

	function fetchPhones($profile)
	{
		try
		{
			$qry = "SELECT * FROM ".$this->profile_table."_phone
				WHERE profile_id='".$profile->getId()."'";
			$stmt = $this->db->prepare($qry);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessagae());
		}
		$phones = $stmt->fetchAll(PDO::FETCH_ASSOC);
		unset($stmt);
		$profile->setPhones($phones);
	}

	function fetchEmails($profile)
	{
		try
		{
			$qry = "SELECT * FROM ".$this->profile_table."_email
				WHERE profile_id='".$profile->getId()."'";
			$stmt = $this->db->prepare($qry);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessagae());
		}
		$emails = $stmt->fetchAll(PDO::FETCH_ASSOC);
		unset($stmt);
		$profile->setEmails($emails);
	}

	function insertProfile($tab_profile)
	{
		$qry = "";
		if( is_array($tab_profile) && (count($tab_profile) > 0) )
		{
			$qry .= "INSERT INTO ".$this->profile_table." (
					firstname,
					lastname,
					surname,
					birthday,
					url,
					note,
					company
				)
				VALUES (
					'".($tab_profile['firstname'])."',
					'".($tab_profile['lastname'])."', 
					'".($tab_profile['surname'])."',
					'".($tab_profile['birthday'])."', 
					'".($tab_profile['url'])."',
					'".($tab_profile['note'])."',
					'".($tab_profile['company'])."' 
				) ";
			try
			{
				$this->db->exec($qry);
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessagae());
			}
			
			$tab_profile['id'] = $this->db->lastInsertId();;
			$profile = new Profile($tab_profile);
			
			return $profile;
		}
		Debug::kill("tab_profile empty");
	}

	function updateProfileQuery($profile, $tab_profile)
	{
		$qry = "";
		if( is_array($tab_profile) && (count($tab_profile) > 0) )
		{
			$qry .= "UPDATE ".$this->profile_table." SET
				firstname='".($tab_profile['firstname'])."', lastname='".($tab_profile['lastname'])."', 
				surname='".($tab_profile['surname'])."', birthday='".($tab_profile['birthday'])."', 
				url='".($tab_profile['url'])."', note='".($tab_profile['note'])."', company='".($tab_profile['company'])."' 
				WHERE id=".$profile->getId()." ; ";
		}
		return $qry;
	}
	
	function insertAddressesQuery($profile, $tab_addresses)
	{
		$qry = "";
		if( is_array($tab_addresses) && (count($tab_addresses) > 0) )
		{
			$values = "";
			foreach($tab_addresses as $tab)
			{
				if( !empty($values) ) $values .= ", ";
				$values .= "(".$profile->getId().", '".($tab['type'])."', '".($tab['poaddress'])."',
					 '".($tab['extaddress'])."', '".($tab['street'])."', 
					 '".($tab['city'])."', '".($tab['region'])."', 
					 '".($tab['postcode'])."', '".($tab['country'])."', '".($tab['coords'])."')";
			}
			$qry .= "INSERT INTO ".$this->profile_table."_address
				 (  `profile_id` ,  `type` ,  `poaddress` ,  `extaddress` ,  
				 `street` ,  `city` ,  `region` ,  `postcode` ,  `country`, `coords`) 
				  VALUES ".$values." ; ";
		}
		return $qry;
	}
	
	function insertPhonesQuery($profile, $tab_phones)
	{
		$qry = "";
		if( count($tab_phones) > 0 )
		{
			$values = "";
			foreach($tab_phones as $tab)
			{
				if( !empty($values) ) $values .= ", ";
				$values .= "(".$profile->getId().", '".($tab['type'])."', '".($tab['number'])."')";
			}
			$qry .= "INSERT INTO ".$this->profile_table."_phone
				 (  `profile_id` ,  `type` ,  `number`  ) VALUES ".$values." ; ";
		}
		return $qry;
	}
	
	function insertEmailsQuery($profile, $tab_emails)
	{
		$qry = "";
		if( count($tab_emails) > 0 )
		{
			$values = "";
			foreach($tab_emails as $tab)
			{
				if( !empty($values) ) $values .= ", ";
				$values .= "(".$profile->getId().", '".($tab['type'])."', '".($tab['email'])."')";
			}
			$qry .= "INSERT INTO ".$this->profile_table."_email
				 (  `profile_id` ,  `type` ,  `email`  ) VALUES ".$values." ; ";
		}
		return $qry;
	}
	
	function insertAll($profile, $tab_addresses, $tab_phones, $tab_emails)
	{
		//No multi-query for PDO::exec -> causes general error "2013 Lost connection to MySQL server during query"
		//tested with PHP5.0, 5.1 & 5.1.2
		//See : http://bugs.php.net/bug.php?id=36228
		
	
		$qry1 = "DELETE FROM ".$this->profile_table."_address WHERE profile_id=".$profile->getId()." ; \n";
		$qry2 = "DELETE FROM ".$this->profile_table."_phone WHERE profile_id=".$profile->getId()." ; \n";
		$qry3 = "DELETE FROM ".$this->profile_table."_email WHERE profile_id=".$profile->getId()." ; \n";
		
		$qry4 = $this->insertAddressesQuery($profile, $tab_addresses);
		$qry5 = $this->insertPhonesQuery($profile, $tab_phones);
		$qry6 = $this->insertEmailsQuery($profile, $tab_emails);
		
		try
		{
			$this->db->exec($qry1);
			$this->db->exec($qry2);
			$this->db->exec($qry3);
			$this->db->exec($qry4);
			$this->db->exec($qry5);
			$this->db->exec($qry6);
		}
		catch(PDOException $e)
		{
			Debug::display($qry);
			Debug::kill($e->getMessage());
		}
	}
	
	function updateAll($profile, $tab_profile, $tab_addresses, $tab_phones, $tab_emails)
	{
		//No multi-query for PDO::exec -> causes general error "2013 Lost connection to MySQL server during query"
		//tested with PHP5.0, 5.1 & 5.1.2
		//See : http://bugs.php.net/bug.php?id=36228
		
		$qry1 = "DELETE FROM ".$this->profile_table."_address WHERE profile_id=".$profile->getId()." ; ";
		$qry2 = "DELETE FROM ".$this->profile_table."_phone WHERE profile_id=".$profile->getId()." ; ";
		$qry3 = "DELETE FROM ".$this->profile_table."_email WHERE profile_id=".$profile->getId()." ; ";
		
		$qry4 = $this->updateProfileQuery($profile, $tab_profile);
		$qry5 = $this->insertAddressesQuery($profile, $tab_addresses);
		$qry6 = $this->insertPhonesQuery($profile, $tab_phones);
		$qry7 = $this->insertEmailsQuery($profile, $tab_emails);

		try
		{
			$this->db->exec($qry1);
			$this->db->exec($qry2);
			$this->db->exec($qry3);
			$this->db->exec($qry4);
			$this->db->exec($qry5);
			$this->db->exec($qry6);
			$this->db->exec($qry7);
		}
		catch(PDOException $e)
		{
			Debug::display($qry);
			Debug::kill($e->getMessage());
		}
	}

}

?>
