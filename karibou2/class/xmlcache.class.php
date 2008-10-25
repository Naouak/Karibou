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
	protected $cachesubdir = "";
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
	
	function loadFile($xml, $use_basename = true)
	{
		if( ! is_file($xml) )
			Debug::kill("File : $xml doesn't exist");
		$this->lastmodified = filemtime($xml);
		
		return $this->load($xml, false, $use_basename);
	}

	function loadURL($xml, $timeout=3600)
	{
		$this->cachesubdir = "/http";
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
	
	function load($xml, $from_url = false, $use_basename = true)
	{
		ExecutionTimer::getRef()->start("XMLCache Load");
		$this->xmlfile = $xml;
		$this->cacheid = md5($xml);
		if( ( $from_url ) || (!$use_basename) )
		{
			$this->cachefile = $this->cachedir.'/'.$this->cacheid.".php";
		}
		else
		{
			$this->cachefile = $this->cachedir.'/'.basename($this->xmlfile).".php";
		}

		if( ! $this->isCached() )
		{
			Debug::display("Creating Cache File ".$this->cachefile);
			if( ! ($xml0 = $this->createCache($from_url)) ) return FALSE;
		}
		else
		{
			$xml0 = unserialize(file_get_contents($this->cachefile));
		}
		$this->xml = $xml0;
		ExecutionTimer::getRef()->stop("XMLCache Load");
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
		//$txt = preg_replace('/\'/', '\\\'', $txt);
		$txt = trim($txt);
		return $txt;
	}
	
	function getXMLElement($simplexml)
	{
		$xml = new XMLElement();
		
		$xml->addText($this->cleantxt((string)$simplexml));
		
		foreach($simplexml->attributes() as $name => $value)
		{
			$xml->addAttribute($name, $this->cleantxt((string)$value) );
		}
		
		foreach($simplexml->children() as $name => $node)
		{
			$child = $this->getXMLElement($node);
			$xml->addChild($name, $child );
		}
		return $xml;
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
			$status = ereg("(.*)://([^/]*)/(.*)$",$url,$regs);
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
		
		if ($protocol == "https")
			$fp = fsockopen("tls://" . $serverName, 443, $errno, $errstr, 2);
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

			fwrite($fp, $out);
			$xmlString = '';
			while (!feof($fp)) 
			{
    		$xmlString .= fread($fp,8192);
			}
			fclose($fp);
		}			
		
		// split header and body
      $pos = strpos($xmlString, "\r\n" . "\r\n");
      if($pos !== false)
      {
      	$header = substr($xmlString, 0, $pos);
      	$xmlString = substr($xmlString, $pos + 2 * strlen("\r\n"));
				$startXML = strpos($xmlString, "<");
				$endXML = strrpos($xmlString, ">");
				$xmlString = substr($xmlString, $startXML, $endXML - $startXML + 1);
			}
			
			// parse headers
      $headers = array();
      $lines = explode("\r\n", $header);
      foreach($lines as $line)
          if(($pos = strpos($line, ':')) !== false)
              $headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1));

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
			if( (filectime($workfile)+60) < time() )
			{
				// si le fichier de travail existe deja on retourne et on prend du coup l'ancienne version
				// ca evite que 2 process ecrivent en meme temps, au pire on a une ancienne version du fichier
				return;
			}
			else
			{
				unlink($workfile);
			}
		}
		
		touch($workfile);
		
		if (!$from_url)
		{
			$simplexml = simplexml_load_file($this->xmlfile);
		}
		else
		{
			$simplexml = simplexml_load_string($this->getXMLFromURL($this->xmlfile));
		}
		
		if( $simplexml === FALSE )
		{
			Debug::display("impossible de parser le fichier xml : " . $this->xmlfile);
			unlink($workfile);
			return FALSE;
		}
		else
		{
			$xmlobj = $this->getXMLElement($simplexml);
			file_put_contents($workfile, serialize($xmlobj));

			if (is_file($this->cachefile))
				unlink($this->cachefile);
			rename($workfile, $this->cachefile);
			return $xmlobj;
		}
	}
}

?>
