<?php

class Mailbox
{
	protected $outencoding;

	protected $imap;
	protected $login;

	protected $msgcount = false;
	protected $connected = false;
	protected $quota = null;

	protected $host;
	protected $perPage = 25;

	protected $hideDeleted = TRUE;
	protected $useTrash = FALSE;
	protected $trashFolder = "INBOX.Trash";
	
	protected $cachedMailboxes = NULL;
	
	function __construct($host, $login, $pass, $folder='INBOX', $port='', $options='/imap/tls/novalidate-cert', $outencoding = "UTF-8")
	{
		$this->outencoding = $outencoding;
		$this->host = $host;
		if( !empty($port) )
		{
			$port = ":".$port;
		}
		$uri = "{".$host.$port.$options."}".$folder;
		$this->connect($uri, $login, $pass);
	}
	
	function __destruct()
	{
		if( $this->connected() )
		{
			imap_close($this->imap);
		}
	}
	
	function connect($uri, $login, $pass)
	{
		if( $this->imap = @imap_open($uri, $login, $pass) )
		{
			$this->connected = true;
		}
	}
	
	function connected()
	{
		return $this->connected;
	}
	
	function setUseTrash($use = TRUE, $folder = 'INBOX.Trash')
	{
		$this->useTrash = $use;
		$this->trashFolder = $folder;
	}
	
	function hideDeleted()
	{
		$this->hideDeleted = TRUE;
	}
	function displayDeleted()
	{
		$this->hideDeleted = FALSE;
	}
	
	function setPerPage($num)
	{
		$this->perPage = $num;
	}
	
	function & getImapStream()
	{
		return $this->imap;
	}
	
	function & getMessageCount()
	{
		if($this->msgcount === false)
		{
			$this->msgcount =  imap_num_msg($this->imap);
		}
		return $this->msgcount;
	}
	
	function getQuota()
	{
		if( ! function_exists('imap_get_quotaroot') ) return false;
		if($this->quota === null)
		{
			if( !($this->quota = @imap_get_quotaroot($this->imap, "INBOX")) )
			{
				$this->quota = false;
			}
		}
		return $this->quota;
	}
	
	function getMessageNumber($uid)
	{
		return imap_msgno($this->imap, $uid);
	}
	function getUid($msgno)
	{
		return imap_uid($this->imap, $msgno);
	}
	
	function getPageCount()
	{
		return ceil($this->getMessageCount()/$this->perPage);
	}

	function & getMailboxes()
	{
		if( $this->cachedMailboxes !== NULL ) return $this->cachedMailboxes;
		
		$this->cachedMailboxes = array();
		if( $list = imap_list($this->imap, "{".$this->host."}",'*') )
		{
			foreach($list as $key => $val)
			{
				$this->cachedMailboxes[$key] = str_replace("{".$this->host."}", '', imap_utf7_decode($val));
			}
			sort($this->cachedMailboxes);
		}
		return $this->cachedMailboxes;
	}
	
	function getHeaders($page=1) //SA_RECENT )
	{
		if( $this->hideDeleted )
		{
			$mail_filter = "UNDELETED";
		}
		else
		{
			$mail_filter = "ALL";
		}
		if( $messages = imap_search($this->imap, $mail_filter) )
		{
			$total_count = count($messages);
			//$messages = array_reverse($messages);
			$seq = "";
			$skip = $this->perPage * ($page);
			$start = $total_count - $skip;
			$end = $start + $this->perPage;
			$count = 0;
			foreach( $messages as $key => $msgno )
			{
				if($count >= $start)
				{
					if( !empty($seq) ) $seq .= ",";
					$seq .= $msgno;
				}
				$count++;
				if( ($count) >= $end) break;
			}
			$headers = imap_fetch_overview($this->imap, $seq);
			Debug::display($seq);
			//return array();
			//Debug::kill($headers);
			foreach( $headers as $h )
			{
				$h->subject = isset($h->subject)?$this->headerDecode($h->subject):"";
				$h->from = isset($h->from)?$this->headerDecode($h->from):"";
			}
			return $headers;
		}
		return array();
	}
	
	function headerDecode($txt, $outencoding='utf-8')
	{
		$elements = imap_mime_header_decode($txt);
		$ret = "";
		foreach($elements as $e)
		{
			switch(strtolower($e->charset))
			{
				case 'utf-8':
					if( $outencoding == 'utf-8' )
					{
						$ret .= $e->text;
					}
					else
					{
						$ret .= utf8_decode($e->text);
					}
					break;
				default:
					if( $outencoding == 'utf-8' )
					{
						$ret .= utf8_encode($e->text);
					}
					else
					{
						$ret .= utf8_decode($e->text);
					}
					break;
			}
		}
		return $ret;
	}
	
	function expunge()
	{
		imap_expunge($this->imap);
	}
	
	function createFolder($folder)
	{
		imap_createmailbox($this->imap, imap_utf7_encode("{".$this->host."}".$folder)) ;
	}
	
	function messageMove($list, $folder)
	{
		imap_mail_move($this->imap, $list, $folder, CP_UID);
		if( $this->useTrash )
		{
			imap_expunge($this->imap);
		}
	}
	
	function messageRemove($uid)
	{
		if( $this->useTrash )
		{
			$mbox = $this->getMailboxes();
			$trash_exist = FALSE;
			foreach($mbox as $box)
			{
				if( $box == $this->trashFolder)
				{
					$trash_exist = TRUE;
					break;
				}
			}
			if( !$trash_exist )
			{
				$this->createFolder($this->trashFolder);
			}
			imap_mail_move($this->imap, $uid, $this->trashFolder, CP_UID);
			$this->expunge();
		}
		else
		{
			imap_delete($this->imap, $uid, FT_UID);
		}
	}
}

?>
