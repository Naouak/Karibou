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

	function loadURL($xml, $timeout=3600)
	{
		$this->lastmodified = time() - $timeout ;
		return $this->load( $xml ) ;
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
	
	function load($xml)
	{
		$this->xmlfile = $xml;
		$this->cacheid = md5($xml);
		$this->cachefile = $this->cachedir.'/'.$this->cacheid.".php";

		if( ! $this->isCached() )
		{
			Debug::display("Creating Cache File ".$this->cachefile);
			if( ! $this->createCache() ) return FALSE;
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
	
	function createCache()
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
		$fp = fopen($workfile, "w");
		
		if( !($simplexml = simplexml_load_file($this->xmlfile)) )
		{
			Debug::display("impossible de parser le fichier xml : $configfile");
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
			rename($workfile, $this->cachefile);
			return TRUE;
		}
	}
	
}

?>
