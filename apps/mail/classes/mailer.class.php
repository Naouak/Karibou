<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

ClassLoader::add('PHPMailer', KARIBOU_LIB_DIR.'/phpmailer/class.phpmailer.php');

class Mailer extends PHPMailer
{
	var $WordWrap = 80;
	var $CharSet = "UTF-8";
    var $Host = "localhost";
    var $Mailer = "smtp";

	function parseFrom($from)
	{
		if( preg_match('/(.*) <([a-z0-9_\-\.]+@[a-z\-\.]+)>/i', $from, $match) )
		{
			$this->From = $match[2];
			$this->FromName = $match[1];
		}
		else
		{
			$this->From = $from;
			$this->FromName = $from;
		}
	}
	
	function parseTo($to)
	{
		$addr_list = imap_rfc822_parse_adrlist($to, "master-comex.com");
		foreach($addr_list as $addr)
		{
			if(!isset( $addr->personal) )
			{
				$addr->personal = $addr->mailbox."@".$addr->host;
			}
			$this->AddAddress($addr->mailbox."@".$addr->host, $addr->personal );
		}
	}
	function parseCC($cc)
	{
		$addr_list = imap_rfc822_parse_adrlist($cc, "master-comex.com");
		foreach($addr_list as $addr)
		{
			if(!isset( $addr->personal) )
			{
				$addr->personal = $addr->mailbox."@".$addr->host;
			}
			$this->AddCC($addr->mailbox."@".$addr->host, $addr->personal );
		}
	}
}
 
?>
