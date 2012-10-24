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
			
		$this->assign('host', $GLOBALS['config']['mail']['server']);
		$this->assign('login', $user->getEmail());

		$krypt = new Krypt();
		$pubkey = $krypt->getPublicKey();
		$this->assign("pubkey_exp", $pubkey["e"]->hex );
		$this->assign("pubkey_mod", $pubkey["n"]->hex );
	}
}
 
?>
