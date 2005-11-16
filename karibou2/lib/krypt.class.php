<?php
/**
 * @copyright 2005 JoN
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package lib
 **/

require_once 'PEAR.php';

ClassLoader::add('Crypt_RSA', 'Crypt/RSA.php');
ClassLoader::add('Crypt_RSA_Math_BigInt', 'Crypt/RSA/Math/BigInt.php');
ClassLoader::add('Crypt_RSA_KeyPair', 'Crypt/RSA.php');
ClassLoader::add('Crypt_RSA_Math_GMP', 'Crypt/RSA.php');


function binToHex($str)
{
	$result = '';
	$n = strlen($str);
	do {
		$dec = strval( ord($str{--$n}) );
		$zero = "";
		if( ($dec) < 16)
			$zero = "0";
		$result .= $zero.dechex($dec);
	} while ($n > 0);
	return $result;	
}

function hexToBin($str)
{
	$result = '';
	$n = strlen($str)-1;
	for( ; $n > 0 ; $n-- )
	{
		$result .= chr(hexdec( $str{$n-1}.$str{$n} ));
		$n--;
		if( $n == 1 )
		{
			$n--;
			$result .= chr(hexdec( $str{$n} ));
		}
	}
	return $result;
}


/**
 * @package lib
 */

class Krypt
{
	protected $keysize = 96;
	protected $keypair;
	
	function __construct()
	{
		if( ! isset($_SESSION['rsakeypair']) )
		{
			$this->keypair = new Crypt_RSA_KeyPair($this->keysize) ;
			if( PEAR::isError($this->keypair) )
			{
				Debug::kill($this->keypair->getMessage());
			}
			else
			{
				session_register('rsakeypair');
				$_SESSION['rsakeypair'] = serialize($this->keypair);
			}
		}
		else
		{
			$this->keypair = unserialize($_SESSION['rsakeypair']);
			if( PEAR::isError($this->keypair) )
			{
				session_unregister('rsakeypair');
				Debug::kill($this->keypair->getMessage());
			}
		}
	}
	
	function getPublicKey()
	{
		return $this->keypair->getPublicKey();
	}
	
	function decrypt($data)
	{
		$rsa = new Crypt_RSA();
		$decrypted = $rsa->decryptBinary(hexToBin($data),  $this->keypair->getPrivateKey() );
		return $decrypted;
	}
}

?>
