<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/



/**
 * Used for Login form
 * 
 * @package applications
 */

class LoginForm extends Model
{
	protected $useRSA = false;
	
	function build()
	{
		if( !$this->currentUser->isLogged() && $this->useRSA )
		{
			$krypt = new Krypt();
			$pubkey = $krypt->getPublicKey();
			$this->assign("pubkey_exp", binToHex($pubkey->getExponent()) );
			$this->assign("pubkey_mod", binToHex($pubkey->getModulus()) );
		}


		$this->assign("isLoggedIn", $this->currentUser->isLogged() );

		if( $this->currentUser->isLogged() )
		{
			$this->assign("username", $this->currentUser->getLogin());
		
			$keychain = KeyChainFactory::getKeyChain($this->currentUser);
			//JoN check please...
			if ($keychain !== FALSE)
			{
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
						//Debug::kill($keychain);
					}
					else
					{
						$this->assign('nokeychain', true);
					}
				}
			}
			else
			{
				$this->assign('nokeychain', true);
			}
		}

		if (isset($GLOBALS['config']['login']['allowaccountcreation'])) {
			$this->assign ("allowaccountcreation", $GLOBALS['config']['login']['allowaccountcreation']);
		} else {
			$this->assign ("allowaccountcreation", TRUE);
		}
	}
}

?>
