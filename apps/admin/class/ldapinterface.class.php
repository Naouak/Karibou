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
 * Display profile
 * 
 * @package applications
 */

define('MAIL_QUOTA', 500000000);

class LDAPInterface
{
	protected $ldapconn;
	
	function __construct()
	{
		
	}
	
	function connect()
	{
		$this->ldapconn = ldap_connect("localhost")
			or die("Unable to connect to LDAP.");

		ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

		if ($this->ldapconn)
		{
			$ds = ldap_bind($this->ldapconn, $GLOBALS["config"]["ldap"]["rdn"], $GLOBALS["config"]["ldap"]["pwd"])
			or die ("Unable to link to LDAP. [".ldap_error($this->ldapconn)."]");
		}
	}
	
	function disconnect()
	{
		ldap_close($this->ldapconn);
	}
	
	function addAccount($login, $host, $pass, $organisationalunits)
	{
		$email = $login.'@'.$host;
		
		$mailbox = $host."/";
		$mailbox .= substr($login,0,1)."/";
		$mailbox .= substr($login,1,1)."/";
		$mailbox .= $login."/";
		
		$quota = MAIL_QUOTA;
		
		$info = array (
			'objectclass' =>
			array (
				0 => 'top',
				1 => 'JammMailAccount',
			),
			'mail' =>
			array (
				0 => $email,
			),
			'mailbox' =>
			array (
				0 => $mailbox,
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
				0 => $quota,
			),
			'accountactive' =>
			array (
				0 => 'TRUE',
			),
			'userpassword' =>
			array (
				0 => "{MD5}" . base64_encode(pack("H*",md5($pass))),
			),
			/*
			'maildrop' =>
			array (
				0 => $email2,
			),
			*/
			'disableimap' =>
			array (
				0 => 'FALSE',
			)
		);
	
		$ouString = "";
		foreach ($organisationalunits as $ou)
		{
			$ouString .= ",ou=".$ou;
		}
				
		
		$ldap_rdn = "mail=".$email.strtolower(trim($ouString)).",".$GLOBALS["config"]["ldap"]["jvd"];
		
		if ( $result = ldap_add($this->ldapconn,$ldap_rdn, $info) )
		{

			Debug::display("1 record imported in LDAP");
			return TRUE;
		}
		else
		{
			Debug::display("Problem importing 1 record in LDAP");
			return ldap_err2str(ldap_error($this->ldapconn)) . ' ['.$ldap_rdn.']';
		}
	}

}

?>
