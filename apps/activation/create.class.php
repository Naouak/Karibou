<?php

class ActivationCreate extends Model
{
	protected $ldapjvd;
	protected $ldaprdn;
	protected $ldappass;
	protected $domain;

	protected $table;

	function build()
	{
		$this->ldaprdn = $GLOBALS['config']['ldap']['rdn'];
		$this->ldappass = $GLOBALS['config']['ldap']['pwd'];
		$this->ldapjvd = $GLOBALS['config']['ldap']['jvd'];
		$this->domain = $GLOBALS['config']['mail']['domain'];
		
		$table = $GLOBALS['config']['bdd']['annuairedb'].".import_alumni";
		$this->table = $table;

		$key = $this->args['key'];
		$this->assign('key', $key);
		
		$qry = "SELECT * FROM $table WHERE active='0000-00-00 00:00:00' AND uniqkey='".$key."'";
		$stmt = $this->db->query($qry);
		if( $item = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			unset($stmt);
			$this->createEmail($item);
			$this->assign('password', $password);
		}
		else
		{
			$this->assign('error', true);
			$this->assign('message', 'compte déjà créé');
		}
	}
/*
	function deleteAccent ($chaine)
	{
		$chaine = str_replace("'", "", $chaine);
		$chaine = str_replace("\'", "", $chaine);
		$chaine = str_replace(" ", "", $chaine);
		 
		return( strtr( $chaine,
		"ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ",
		"AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn" ) );
	}
*/
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
		$pass = "";
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

		//$email = strtolower($this->deleteAccent($item['prenom'])).".".strtolower($this->deleteAccent($item['nom']))."@".$this->domain;
		$email = str_replace(" ", "", strtolower (KText::epureString(trim($item['prenom']).".".trim($item['nom']) )."@".$this->domain));
		$lafiliere = $item["promotype"];
		$lapromo = $item["promo"];
		
		$add_email = $email;
		$add_mailbox = $GLOBALS['config']['mail']['domain']."/";
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
		$sr = ldap_search ($ldapconn, $this->ldapjvd,"(&(objectClass=organizationalUnit)(ou=".$ou."))");
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
			@ldap_add($ldapconn, "ou=".$ou.",".$this->ldapjvd, $info_add);
		}
		
		if ( @ldap_add($ldapconn, "mail=".$add_email.",ou=".$ou.",".$this->ldapjvd, $info) )
		{
			$req_sql = "UPDATE ".$this->table." SET active = NOW(), email='".$email_alternatif."' WHERE id = ".$id;
	
			$this->db->exec($req_sql);
			
			ldap_close($ldapconn);
			
			$this->assign("email", $add_email);
			$this->assign("password", $add_password);
			$this->send_email_create($email_alternatif, $email,  $add_password);
	  }
	  else 
	  {
			ldap_close($ldapconn);
			$this->assign('error', true);
			$this->assign('message', 'Une erreur s\'est produite. Peut-être ce compte a-t-il déja été activé ?');
	  }
		
	}
	
	/**
	 * Fonction pour l'envoie d'email de confirmation de création
	 */
	
	function send_email_create ($email_alternatif,$email_enic, $add_password) {
	
		global $add_email;
	
		$subject = "[".$GLOBALS['config']['mail']['domain']."] Bienvenue $add_email";
	
		$message = "
<html>
 <head>
  <title>".$subject."</title>
  <style type=\"text/css\">
   p {
     font-family: Trebuchet MS, Verdana, Arial;
     font-size: 11px;
   }
   a {
     color: #225599;
   }
   a:hover {
     color: #995566;
   }
   .link {
     font-size: 13px;
   }
   #hint {
     font-size: 12px;
     background: #cfcfff;
     border: 1px solid #adadee;
     padding: 2px;
   }
   #hint p{
     margin: 0px;
     padding: 0px;
   }
  </style>
 </head>
 <body>

Bonjour,<br />
<br />
Ton compte email $add_email est désormais actif.<br />
<br />
Ton mot de passe est: $add_password
<br />
<br />
Tu peux le changer sur l'<a href=\"http://intranet.".$GLOBALS['config']['mail']['domain']."\">intranet</a>, dans la rubrique correspondante.
<br />
<br />
A bientôt !<br />
<br />
L'équipe Inkateo.
<a href=\"mailto:contact@inkateo.com\">contact@inkateo.com</a>

 </body>
</html>";

	/* Pour envoyer du mail au format HTML, vous pouvez configurer le type Content-type. */
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	
	/* D'autres en-têtes : errors, From cc's, bcc's, etc */
	$headers .= "From: Inkateo <contact@inkateo.com>\r\n";
	
	/* Send it ! 
			Envoi également à l'adresse fournie par l'ancien si elle existe
	*/
		if( isset($email_alternatif) && $email_alternatif ) $sent_adresses = $add_email.",".$email_alternatif;
		else $sent_adresses = $add_email;

		if (isset($email_enic) && $email_enic ) { $sent_adresses .= ",".$email_enic; }


		mail($sent_adresses, $subject, $message, $headers);

	}
}

?>