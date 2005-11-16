<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class Mail extends Model
{
	function build()
	{
		$user = $this->userFactory->getCurrentUser();
		if( ! ($mailconnections = $user->getPref("mail_connections")) )
		{
			$mailconnections = array();
		}
		
		$this->assign('connections', $mailconnections);
			
		$this->assign('host', "mail.telecomlille.net");
		$this->assign('login', $user->getEmail());

		$krypt = new Krypt();
		$pubkey = $krypt->getPublicKey();
		$this->assign("pubkey_exp", binToHex($pubkey->getExponent()) );
		$this->assign("pubkey_mod", binToHex($pubkey->getModulus()) );
	}
}
 
?>
