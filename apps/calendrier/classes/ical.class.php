<?php 

/**
 * @version $Id: ical.class.php,v 1.2 2004/12/03 00:52:56 jon Exp $
 * @copyright 2005 Benoit Tirmarche <benoit.tirmarche@telecomlille.net>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

require_once 'File.php';

class ICal
{
	protected $version;
	protected $prodid;
	
	protected $filename;
	protected $line;
	protected $eventList;
	
	function __construct($filename)
	{
		if(file_exists($filename))
		{
			$this->filename = $filename;
			$this->parse();
		}
		else
		{
			Debug::kill("Impossible d'ouvrir le fichier $filename");
		}
	}
	
	function parse()
	{
		$file = new File();
// 		$iCalLine = new ICalLine('','');
		$this->eventList = new ObjectList();
		
		$beginEvent = FALSE;
		
		while($line = $file->readline($this->filename))
		{
			if($iCalLine = $this->parseLine($line))
			{
				switch($iCalLine->getField())
				{
					case 'VERSION':
							$this->version = $iCalLine->getData();
							break;
					case 'PRODID' :
							$this->prodid = $iCalLine->getData();
							break;
					case 'BEGIN'	:
							if($iCalLine->getData() == 'VEVENT' && !$beginEvent)
							{
								$event = new ICalEvent();
								$beginEvent = TRUE;
							}
							break;
					case 'END' : 
							if($iCalLine->getData() == 'VEVENT' && $beginEvent)
							{
								$this->eventList[] = $event;
	// 							unset($event);
								$beginEvent = FALSE;
							}
							break;
					default :
							if($beginEvent)
							{
								$event->addData($iCalLine->getField(), $iCalLine->getData());
							}
				}
				unset($iCalLine);
			}

			
		}
		$file->closeAll();
		
		foreach($this->eventList as $event)
		{
			Debug::display($event);
		}
	}
	
	function parseLine($line)
	{
		if(isset($line))
		{
			if(preg_match('/^([A-Z]+):(.+)/', $line, $match))
			{
				return new ICalLine($match[1], $match[2]);
			}
			else
			{
				return FALSE;
			}
		}
	}
}

class ICalLine
{
	protected $field;
	protected $data;
	
	function __construct($field, $data)
	{
		$this->field = $field;
		$this->data = $data;
	}
	
	function getField()
	{
		return $this->field;
	}
	
	function getData()
	{
		return $this->data;
	}
}

class ICalEvent
{
	protected $data = array();
	
	function __construct()
	{
	}
	
	function addData($field, $data)
	{
		$this->data[$field] = $data;
	}
	
	function getData()
	{
		return $this->data;
	}
}
?>