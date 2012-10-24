<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class KeyChainConfig extends Model
{
	function build()
	{
		$krypt = new Krypt();
		$pubkey = $krypt->getPublicKey();

		$this->assign("pubkey_exp", $pubkey["e"]->hex );
		$this->assign("pubkey_mod", $pubkey["n"]->hex );

		$keychain = KeyChainFactory::getKeyChain($this->currentUser);
		if( $keychain->unlock() )
		{
			$this->assign('unlocked', true);
		}
		else
		{
			$names = $keychain->getNames();
			if( in_array('keychain_check', $names) )
			{
				$this->assign('oldlock', true);
			}
			else
			{
				$this->assign('nokeychain', true);
			}
		}
	}
}
 
?>
