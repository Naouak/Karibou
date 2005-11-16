<?php

require_once dirname(__FILE__).'/part.class.php';

class Message
{
	protected $outencoding;

	protected $uid;
	protected $mid;
	protected $imap;
	protected $mbox;
	protected $structure = false;
	
	function __construct(&$mbox, $uid, $outencoding = 'UTF-8')
	{
		$this->mbox = $mbox;
		$this->imap = $mbox->getImapStream();
		$this->uid = $uid;
		$this->mid = $mbox->getMessageNumber($uid);
		$this->outencoding = $outencoding;
	}
	
	function getStructure()
	{
		if( $this->structure === false )
		{
			$this->structure = imap_fetchstructure($this->imap, $this->mid);
		}
		return $this->structure;
	}
	
	function getHeader()
	{
		$header = imap_headerinfo($this->imap, $this->mid);
		$header->subject = $this->mbox->headerDecode($header->subject);
		
		if(isset($header->to)) $this->filterAddresses($header->to);
		if(isset($header->from)) $this->filterAddresses($header->from);
		if(isset($header->cc)) $this->filterAddresses($header->cc);
		if(isset($header->reply_to)) $this->filterAddresses($header->reply_to);
		
//		Debug::display($header);
		return $header;
	}
	
	function getHeaderPart()
	{
		$struct = $this->getStructure();
//		Debug::display($struct);
		$p = new Part($this->imap, $this->mid, '0', $struct, TRUE);
		return $p;
	}
	
	function getBody()
	{
		$struct = $this->getStructure();
//		Debug::display($struct);
		$p = new Part($this->imap, $this->mid, '1', $struct, TRUE);
		return $p->getBody();
	}
	
	function getAttachments()
	{
		$struct = $this->getStructure();
		$att = array();
		if( isset($struct->parts) )
		{
			foreach($struct->parts as $key => $part)
			{
				if( ($part->ifdisposition) && ($part->disposition == 'attachment') )
				{
					$p = new Part($this->imap, $this->mid, $key+1 , $part);
					$att[] = $p;
				}
			}
		}
		return $att;
	}
	
	function filterAddresses($array)
	{
		foreach($array as $addr)
		{
			if( isset($addr->personal) )
			{
				$addr->personal = MailBox::headerDecode($addr->personal) ;
			}
			else
			{
				$addr->personal = $addr->mailbox."@".$addr->host;
			}
		}
	}
}

?>