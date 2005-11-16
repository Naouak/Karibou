<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/**
 * @package applications
 */
class PasswordInterfaceLDAP extends PasswordInterface
{

	protected $ldapconn;
	protected $jvd;
	
	function __construct($ldaprdn, $ldappass, $jvd)
	{
		$this->jvd = $jvd;
	
		$this->ldapconn = ldap_connect("localhost")
			or die("Unable to connect to LDAP server.");
		
		ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

		if ($this->ldapconn)
		{
			$ds = ldap_bind($this->ldapconn, $ldaprdn, $ldappass)
				or die ("Unable to link to LDAP server. [".ldap_error($this->ldapconn)."]");
		}
	}

	function dnFromEmail ($email, $searchdn) {
		  $filter = "mail=".$email;
		  $sr = ldap_search($this->ldapconn, $searchdn, $filter);
		  $info = ldap_get_entries($this->ldapconn, $sr);
		  return $info[0]["dn"];
	}

	function changePassword($email, $newpassword)
	{
		//Encodage du mot de passe en MD5 pour l'enregistrement dans le LDAP
		$new["userPassword"] = "{MD5}" . base64_encode(pack("H*",md5($newpassword)));
		
		$mydn = $this->dnFromEmail ($email, $this->jvd);
		
		if (ldap_modify($this->ldapconn, $mydn, $new))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

?>
