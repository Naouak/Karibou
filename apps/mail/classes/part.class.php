<?php

/*
	0 TYPETEXT (integer)
	1 TYPEMULTIPART (integer)
	2 TYPEMESSAGE (integer)
	3 TYPEAPPLICATION (integer)
	4 TYPEAUDIO (integer)
	5 TYPEIMAGE (integer)
	6 TYPEVIDEO (integer)
	7 TYPEOTHER (integer)
	
	0 ENC7BIT (integer)
	1 ENC8BIT (integer)
	2 ENCBINARY (integer)
	3 ENCBASE64 (integer)
	4 ENCQUOTEDPRINTABLE (integer)
	5 ENCOTHER (integer)
*/

class Part
{
	protected $outencoding;

	protected $imap;
	protected $mid;
	protected $pid;
	protected $part;
	protected $root;
	protected $attributes = array();

	protected $types = array(	
		0 => 'TEXT' ,
		1 => 'MULTIPART' ,
		2 => 'MESSAGE' ,
		3 => 'APPLICATION' ,
		4 => 'AUDIO' ,
		5 => 'IMAGE' ,
		6 => 'VIDEO' ,
		7 => 'OTHER' );

	protected $html_forbids = array ( 
		'@<html[^>]*?>@i', '@</html>@i' ,
		'@<body[^>]*?>@i', '@</body>@i' ,
		'@<head[^>]*?>.*?</head>@si' ,
		'@<style[^>]*?>.*?</style>@si' ,
		'@<script[^>]*?>.*?</script>@si' ,
		'@<\!DOCTYPE[^>]*?>@si' ,
		'@<meta[^>]*?>@si' ,
		);
	
	function __construct(&$imap, $mid, $pid, $part_structure=false, $root=FALSE, $outencoding = "UTF-8")
	{
		$this->outencoding = $outencoding;
		$this->imap = $imap;
		$this->mid = $mid;
		$this->pid = $pid;
		$this->root = $root;
		
		//print_r($part_structure);
		if( $part_structure !== false )
		{
			$this->part = $part_structure;
			
			if( $part_structure->ifdparameters )
			{
				foreach($part_structure->dparameters as $param)
				{
					$this->attributes[$param->attribute] = $param->value;
				}
			}
			if( $part_structure->ifparameters )
			{
				foreach($part_structure->parameters as $param)
				{
					$this->attributes[$param->attribute] = $param->value;
				}
			}
		}
	}
	
	function getAttributes()
	{
		return $this->attributes;
	}
	
	function getPartNo()
	{
		return $this->pid;
	}
	
	function getBytes()
	{
		return $this->part->bytes;
	}
	function getSize()
	{
		return $this->part->bytes." bytes";
	}
	function getType()
	{
		return $this->part->type;
	}
	function getSubtype()
	{
		return strtolower($this->part->subtype);
	}
	
	function getPart()
	{
		$part = imap_fetchbody($this->imap, $this->mid, $this->pid);
		switch($this->part->encoding)
		{
			case ENC7BIT:
				break;
			case ENC8BIT:
				break;
			case ENCBASE64:
				$part = base64_decode($part);
				break;
			case ENCQUOTEDPRINTABLE:
				$part = imap_qprint($part);
				break;
			case ENCBINARY:
			default:
				break;
		}
		//Debug::kill($part);
		if( $this->part->type != TYPETEXT )
		{
			return $part;
		}
		switch($this->outencoding)
		{
			case 'UTF-8':
				if( !isset($this->attributes['charset']) || ($this->attributes['charset']!='utf-8') )
				{
					$part = utf8_encode($part);
				}
				break;
			default:
				if( isset($this->attributes['charset']) && ($this->attributes['charset']=='utf-8') )
				{
					$part = utf8_decode(imap_utf8($part));
				}
		}
		return $part;
	}
	
	function getBody()
	{
		switch( $this->part->type )
		{
			case TYPETEXT:
				if( strtolower($this->part->subtype) == 'plain' )
				{
					return $this;
				}
				else if ( strtolower($this->part->subtype) == 'html' )
				{
					return $this;
				}
				else if ( strtolower($this->part->subtype) == 'alternative' )
				{
					return $this->getAlternativeBody();
				}
				else
				{
					break;
				}
			case TYPEMULTIPART:
				return $this->getAlternativeBody();
				break;
		}
		Debug::kill($this->getStructure());
	}
	
	function getAlternativeBody()
	{
		if( $ret = $this->searchPart('TEXT/HTML') )
		{
			return $ret;
		}
		else
		{
			$ret = $this->searchPart('TEXT/PLAIN');
			return $ret;
		}
	}
	
	function searchPart($type)
	{
		$fulltype = $this->getContentType();
		if( strtolower($fulltype) == strtolower($type) )
		{
			return $this;
		}
		else if( isset($this->part->parts) )
		{
			foreach( $this->part->parts as $key => $part )
			{
				if( $this->root )
				{
					$subpid = $key+1;
				}
				else
				{
					$subpid = $this->pid.'.'.($key+1);
				}
				$p = new Part($this->imap, $this->mid, $subpid, $part);
				if( $ret = $p->searchPart($type) )
				{
					return $ret;
				}
			}
		}
		return false;
	}
	
	function getContentType()
	{
		return $this->types[$this->part->type]."/".$this->part->subtype;
	}
	
	function cleanHTML($html)
	{
		return preg_replace($this->html_forbids, '', $html);
	}
}

?>