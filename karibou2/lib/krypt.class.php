<?php
/**
 * @copyright 2005 JoN
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package lib
 **/

// Including files on which Krypt depends (Pear classes)
require_once 'PEAR.php';
require_once("Crypt/RSA.php");

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
		if(! isset($_SESSION['rsakeypair']) )
		{
			$rsa = new Crypt_RSA();
			$rsa->publicKeyFormat = CRYPT_RSA_PUBLIC_FORMAT_RAW;

			$this->keypair = $rsa->createKey($this->keysize) ;
			$_SESSION['rsakeypair'] = serialize($this->keypair);
		}
		else
		{
			$this->keypair = unserialize($_SESSION['rsakeypair']);
		}
	}
	
	function getPublicKey()
	{
		return $this->keypair["publickey"];
	}
	
	function decrypt($data)
	{
		$rsa = new Crypt_RSA();
		$decrypted = $rsa->decrypt(hexToBin($data),  $this->keypair["privatekey"] );
		return $decrypted;
	}
}

?>
