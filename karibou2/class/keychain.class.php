<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

/**
 * @package framework
 */
abstract class KeyChain
{

	abstract function createStorage();
	abstract function getData($name);
	abstract function setData($name, $data);
	abstract function getAllData();
	abstract function getNames();


	protected $user;

	protected $use_mcrypt;

	protected $td;
	protected $key;
	protected $ivsize;
	
	function __construct(CurrentUser $user)
	{
		$this->user = $user;
		if( function_exists('mcrypt_module_open') )
		{
			$this->td = mcrypt_module_open(MCRYPT_3DES, '', 'ofb', '');
			$this->ivsize = mcrypt_enc_get_iv_size($this->td);
			$this->use_mcrypt = TRUE;
		}
		else
		{
			$this->use_mcrypt = FALSE;
		}
	}
	
	function __destruct()
	{
		if( $this->use_mcrypt )
		{
			mcrypt_module_close($this->td);
		}
	}
	
	function create($passphrase = false)
	{
		if( $this->use_mcrypt )
		{
			if( $passphrase === false )
			{
				$passphrase = $this->user->getPassPhrase();
			}
			$keysize = mcrypt_enc_get_key_size($this->td);
			$this->key = substr(sha1($passphrase), 0, $keysize);
			$this->set('keychain_check', 'check integrity');
		}
	}
	
	function unlock($passphrase = false)
	{
		if( $this->use_mcrypt )
		{
			if( $passphrase === false )
			{
				$passphrase = $this->user->getPassPhrase();
			}
			$keysize = mcrypt_enc_get_key_size($this->td);
			$this->key = substr(sha1($passphrase), 0, $keysize);
			
			if( $data = $this->getData('keychain_check') )
			{
				if( $this->decrypt($data) == "check integrity" )
				{
					return TRUE;
				}
				else
				{
					$this->key = FALSE;
					return FALSE;
				}
			}
			return FALSE;
		}
		else
		{
			$this->key = "nokey";
			return TRUE;
		}
	}
	
	function relock($passphrase)
	{
		if( $this->unlock() )
		{
			$tab = $this->getAllData();
			Debug::display($tab);
			$dec = array();
			foreach($tab as $k => $t)
			{
				$dec[$k] = $this->decrypt($t);
			}
			$this->create($passphrase);
			foreach($dec as $k => $t)
			{
				$this->set( $k, $t );
			}
		}
	}
	
	function set($name, $data)
	{
		Debug::display("set : ".$name);
		$encrypted =  $this->encrypt($data) ;
		$this->setData($name, $encrypted);
	}

	function get($name)
	{
		Debug::display("get : ".$name);
		return $this->decrypt($this->getData($name));
	}

	function encrypt($str)
	{
		if( $this->use_mcrypt )
		{
			if( $this->key )
			{
				$iv = mcrypt_create_iv($this->ivsize, MCRYPT_DEV_RANDOM);
				mcrypt_generic_init($this->td, $this->key, $iv);
				$encrypted = mcrypt_generic($this->td, $str);
				mcrypt_generic_deinit($this->td);
				return base64_encode('m'.$iv.$encrypted);
			}
			return false;
		}
		else
		{
			Debug::display("warning : keychain is not secure (no encryption)");
			return base64_encode('b'.$str);
		}
	}
	
	function decrypt($str)
	{
		if ($str !== FALSE)
		{
			$str = base64_decode($str);
			Debug::display("Data to decrypt : ".$str);
			$enctype = substr($str, 0, 1);
			$decrypted = FALSE;
			switch($enctype)
			{
				case 'm':
					if( !$this->use_mcrypt )
					{
						Debug::kill("Tried to decrypt an unsupported format (need mcrypt extension)");
					}
					if( $this->key )
					{
						$iv = substr($str, 1, $this->ivsize);
						$data = substr($str, $this->ivsize+1, strlen($str)-$this->ivsize);
						mcrypt_generic_init($this->td, $this->key, $iv);
						$decrypted = mdecrypt_generic($this->td, $data);
						mcrypt_generic_deinit($this->td);
					}
					else
					{
						Debug::display("decrypt : KeyChain is locked or key does not exist");
					}
					break;
				case 'b':
					$decrypted = substr($str, 1, strlen($str)-1);
					break;
				default:
					Debug::kill("Tried to decrypt an unsupported format");
					break;
			}
			return $decrypted;
		}
		else
		{
			Debug::kill("Tried to decrypt FALSE");			
		}
	}

}

?>
