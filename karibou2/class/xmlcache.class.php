<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 *
 * @package framework
 **/
 
require_once dirname(__FILE__)."/xmlelement.class.php";
 
/**
 * @package framework
 */
class XMLCache
{
	protected $xmlfile;
	protected $cachefile;
	protected $xml;
	protected $cacheid;
	protected $cachedir;
	protected $lastmodified;
	
	public function __construct($cachedir=KARIBOU_CACHE_DIR)
	{
		if( ! is_dir($cachedir) )
		{
			mkdir($cachedir);
		}
		$this->cachedir = $cachedir;
	}
	
	public function &getXML()
	{
		return $this->xml;
	}
	
	function loadFile($xml)
	{
		if( ! is_file($xml) )
			Debug::kill("File : $xml doesn't exist");
		$this->lastmodified = filemtime($xml);
		
		return $this->load($xml);
	}

	function loadURL($xml, $timeout=0)
	{
		$this->lastmodified = time() - $timeout ;
		return $this->load( $xml, true) ;
	}
	
	protected function isCached()
	{
		if( is_file($this->cachefile) && 
			($this->lastmodified < filemtime($this->cachefile)) )
		{
			return true;
		}
		return false;
	}
	
	function load($xml, $from_url = false)
	{
		$this->xmlfile = $xml;
		$this->cacheid = md5($xml);
		$this->cachefile = $this->cachedir.'/'.$this->cacheid.".php";

		if( ! $this->isCached() )
		{
			Debug::display("Creating Cache File ".$this->cachefile);
			if( ! $this->createCache($from_url) ) return FALSE;
		}
		include($this->cachefile);
		$this->xml = &$xml0;
		return TRUE;
	}
	
	function getCacheId()
	{
		return $this->cacheid;
	}
	function getLastModified()
	{
		return $this->lastmodified;
	}
	function getCacheDir()
	{
		return $this->cachedir;
	}
	
	function getIndent($level)
	{
		$ret = "";
		for( $i=0 ; $i<$level ; $i++ )
		{
			$ret .= "\t";
		}
		return $ret;
	}
	
	function cleantxt($txt)
	{
		$txt = preg_replace('/[\r\n]+/', '', $txt);
		$txt = preg_replace('/\'/', '\\\'', $txt);
		$txt = trim($txt);
		return $txt;
	}
	
	function getPHPCode($simplexml, $level=0)
	{
		$code = $this->getIndent($level).'$xml'.$level.' = new XMLElement();'."\n";
		$code .= $this->getIndent($level).'$xml'.$level.'->addText(\''.$this->cleantxt((string)$simplexml).'\');'."\n";
		
		foreach($simplexml->attributes() as $name => $value)
		{
			$code .= $this->getIndent($level).'$xml'.$level.'->addAttribute(\''.$name.'\', \''.$this->cleantxt((string)$value).'\');'."\n";
		}
		
		foreach($simplexml->children() as $name => $node)
		{
			$code .= $this->getPHPCode($node, $level+1);
			$code .= $this->getIndent($level).'$xml'.$level.'->addChild(\''.$name.'\', $xml'.($level+1).' );'."\n";
		}
		
		return $code;
	}

	// Load from an url via fsockopen
	function getXMLFromURL($url)
	{
			Debug::display("URL = " . $url);
			if (strpos($url, "@") !== FALSE)
			{
				$status = ereg("(.*)://(.*):(.*)@([^/]*)/(.*)$",$url,$regs);
				$hasPassword = TRUE;
			}
			else
			{
				$status = ereg("(.*)://(.*)/(.*)",$url,$regs);
				$hasPassword = FALSE;
			}
			if($status === FALSE)
			{
				Debug::display("impossible de determiner le serverName");
				return FALSE;
			}
			
			$protocol = $regs[1];
			
			if ($hasPassword)
			{
				$username = $regs[2];
				$password = $regs[3];
				$serverName = $regs[4];
				$fileName = $regs[5];
			}
			else
			{
				$serverName = $regs[2];
				$fileName = $regs[3];
			}
			
			//var_dump($regs);
			//echo "proto = " . $protocol . " servername = " . $serverName . " filename = " .$fileName;

			if ($protocol == "https")
				$fp = fsockopen("ssl://" . $serverName, 443, $errno, $errstr, 2);
			else
				$fp = fsockopen($serverName, 80, $errno, $errstr, 2);
			if (!$fp)
			{
				Debug::display("impossible de d'ouvrir le socket : $errstr");
				return FALSE;
			}
			else
			{
   			$out = "GET /$fileName HTTP/1.1\r\n";
   			$out .= "Host: $serverName\r\n";
   			if ($hasPassword)
   				$out .= "Authorization: Basic ".base64_encode("$username:$password")."\r\n";
   			$out .= "Connection: Close\r\n\r\n";

				Debug::display("out = " . $out);
				fwrite($fp, $out);
				$xmlString = '';
   			while (!feof($fp)) 
   			{
       		$xmlString .= fread($fp, 8192);
   			}
   			fclose($fp);
			}			
			
			// split header and body
      $pos = strpos($xmlString, "\r\n" . "\r\n");
      if($pos !== false)
      {
      	$header = substr($xmlString, 0, $pos);
      	$xmlString = substr($xmlString, $pos + 2 * strlen("\r\n"));
				$xmlData = ereg("([^<]*)(.*)([^>]*)",$xmlString,$regs);
      	if ($xmlData !== FALSE)
					$xmlString = $regs[2];
			}
			
			// parse headers
      $headers = array();
      $lines = explode("\r\n", $header);
      foreach($lines as $line)
          if(($pos = strpos($line, ':')) !== false)
              $headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1));

 			Debug::display("xmlString = " . $xmlString);
      // redirection?
      if(isset($headers['location']))
      {
          return($this->getXMLFromURL($headers['location']));
      }
      else
      {
          return($xmlString);
      }
	}	

	
	function createCache($from_url)
	{
		$workfile = $this->cachefile.".work";
		if( is_file($workfile) )
		{
			/*if( (filectime($workfile)+60) < time() )
			{
				// si le fichier de travail existe deja on retourne et on prend du coup l'ancienne version
				// ca evite que 2 process ecrivent en meme temps, au pire on a une ancienne version du fichier
				return;
			}
			else
			{*/
				unlink($workfile);
			//}
		}
		$fp = fopen($workfile, "w");
		
		if (!$from_url)
		{
			$simplexml = simplexml_load_file($this->xmlfile);
		}
		else
		{
			$simplexml = simplexml_load_string($this->getXMLFromURL($this->xmlfile));
		}
		
		if( !($simplexml) )
		{
			Debug::display("impossible de parser le fichier xml : " . $this->xmlfile);
			fclose($fp);
			unlink($workfile);
			return FALSE;
		}
		else
		{
			$phpfile = "<?php\n";
			$phpfile .= $this->getPHPCode($simplexml);
			$phpfile .= " ?>\n";
			
			fwrite($fp, $phpfile);
			fclose($fp);
			if (is_file($this->cachefile))
				unlink($this->cachefile);
			rename($workfile, $this->cachefile);
			return TRUE;
		}
	}
}

?>
