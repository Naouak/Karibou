<?php

class ActivationCreate extends Model
{
	protected $domain = "master-comex.com";
	protected $ldapbase  = 'dc=inkateo,dc=com';
	protected $ldaprdn  = 'cn=admin,dc=inkateo,dc=com';
	protected $ldappass = '';

	protected $table;

	function build()
	{
		$table = $GLOBALS['config']['bdd']['annuairedb'].".import_alumni";
		$this->table = $table;

		$key = $this->args['key'];
		$this->assign('key', $key);
		
		$qry = "SELECT * FROM $table WHERE active='0000-00-00 00:00:00' AND uniqkey='".$key."'";
		$stmt = $this->db->query($qry);
		if( $item = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$this->createEmail($item);
			$this->assign('password', $password);
		}
		else
		{
			$this->assign('error', true);
		}
		unset($stmt);
	}
	
	function deleteAccent ($chaine)
	{
		$chaine = str_replace("'", "", $chaine);
		$chaine = str_replace("\'", "", $chaine);
		$chaine = str_replace(" ", "", $chaine);
		 
		return( strtr( $chaine,
		"ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ",
		"AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn" ) );
	}

	function generate_password($length)
	{
		$vowels = array("a",  "e",  "i",  "o",  "u",  "ae",  "ou",  "io",  
		                "ea",  "ou",  "ia",  "ai"); 
		$consonants = array("b",  "c",  "d",  "g",  "h",  "j",  "k",  "l",  "m",
		                    "n",  "p",  "r",  "s",  "t",  "u",  "v",  "w",  
			            "tr",  "cr",  "fr",  "dr",  "wr",  "pr",  "th",
			            "ch",  "ph",  "st",  "sl",  "cl");
		$vowel_count = count($vowels);
		$consonant_count = count($consonants);
		for ($i = 0; $i < $length; ++$i)
		{
			$pass .= $consonants[rand(0,  $consonant_count - 1)] .
			$vowels[rand(0,  $vowel_count - 1)];
		}
		return substr($pass,  0,  $length-3).rand(100,999);
	}
	
	function createEmail($item)
	{
		$id = $item['id'];
		$email_alternatif = $_SESSION['activation_email'];

		$email = $this->deleteAccent($item['prenom'])
			.".".$this->deleteAccent($item['nom'])."@".$this->domain;
		$lafiliere = $item["promotype"];
		$lapromo = $item["promo"];
		
		$add_email = $email;
		$add_mailbox = "telecomlille.net/";
		$add_mailbox .= substr($add_email,0,1)."/";
		$add_mailbox .= substr($add_email,1,1)."/";
		$add_mailbox .= substr($add_email,0,strpos($add_email,'@'))."/";
		
		// password setté
		$add_password = $this->generate_password(8);
		$add_quota = '15000000';

		$info = array (
		  'objectclass' => 
		  array (
		    0 => 'top',
		    1 => 'JammMailAccount',
		  ),
		  'mail' => 
		  array (
		    0 => $add_email,
		  ),
		  'mailbox' => 
		  array (
		    0 => $add_mailbox,
		  ),
		  'homedirectory' => 
		  array (
		    0 => '/home/vmail/',
		  ),
		  'delete' => 
		  array (
		    0 => 'FALSE',
		  ),
		  'quota' => 
		  array (
		    0 => $add_quota,
		  ),
		  'accountactive' => 
		  array (
		    0 => 'TRUE',
		  ),
		  'userpassword' => 
		  array (
		    0 => $add_password,
		  ),
		  'disableimap' => 
		  array (
		    0 => 'FALSE',
		  )
		);
		
		
	    ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
	
	    $ldapconn = ldap_connect("localhost")
	      or die("Impossible de se connecter au serveur LDAP.");
	    if ($ldapconn)
	    {
	      $ds = ldap_bind($ldapconn, $this->ldaprdn, $this->ldappass)
	        or die ("Impossible de faire la liaison avec le serveur LDAP.");
	   }
	   
		$ou = strtolower($lapromo);
		$sr = ldap_search ($ldapconn, "jvd=".$this->domain.",".$this->ldapbase,"(&(objectClass=organizationalUnit)(ou=".$ou."))");
		$info_fetched = ldap_get_entries($ldapconn, $sr);
		
		if( $info_fetched['count'] < 1 )
		{
			$info_add = array (
			  'objectclass' => 
			  array (
			    0 => 'top',
			    1 => 'organizationalUnit'
			  ),
			  'ou' => 
			  array (
			    0 => $ou
			  ) ) ;
			@ldap_add($ldapconn, "ou=".$ou.",jvd=".$this->domain.",".$this->ldapbase, $info_add);
		}
		
		if ( @ldap_add($ldapconn, "mail=".$add_email.",ou=".$ou.",jvd=".$this->domain.",o=".$this->ldapbase, $info) )
		{
			// insert OK / A CHANGER !!!
	
	
			$req_sql = "UPDATE ".$this->table." SET active = NOW(), email='".$email."' WHERE id = ".$id;
	
			$this->db->exec($req_sql);
			
			ldap_close($ldapconn);
			
			$this->assign("email", $add_email);
			$this->assign("password", $add_password);
	  }
	  else 
	  {
			ldap_close($ldapconn);
			$this->assign('error'; true);
			$this->assign('message', 'Une erreur s\'est produite. Peut-être ce compte a-t-il déja été activé ?');
	  }
		
	}
}

?>