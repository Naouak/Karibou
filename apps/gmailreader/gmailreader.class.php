<?php 
/**
 * @version $Id:  preferenceslarge.class.php,v 0.1 2005/06/26 10:52:56 dat Exp $
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/** 
 * 
 * @package applications
 **/
class gmailReader extends Model
{
	public function build()
	{
		$gmaillogin = $this->userFactory->getCurrentUser()->getPref('gmaillogin');
		$gmailpass = $this->userFactory->getCurrentUser()->getPref('gmailpass');
		$gmailmax = $this->userFactory->getCurrentUser()->getPref('gmailmax');				
//		if( isset($this->args['feed'], $gmailmax) )
		if( isset($gmaillogin, $gmailpass, $gmailmax) )
		{
			$rss_file = "https://".$gmaillogin.":".$gmailpass."@mail.google.com/mail/feed/atom";
//			echo $rss_file;
			$rss_feed = new XMLCache(KARIBOU_CACHE_DIR.'/xml_rss');
			if( $rss_feed->loadURL($rss_file) )
			{
				$xml = $rss_feed->getXML();
//				Debug::kill($xml);
				$title = "No suitable Feed found";
				$items = array();
				$i = 0;
				
				if( isset($xml->channel, $xml->channel[0]->item) )
				{
					foreach($xml->channel[0]->item as $item)
					{
						$i++;
						if( $i > $gmailmax ) break;
						$items[] = array('title' => $item->title[0] , 'link' => $item->link[0]);
					}
					$title = $xml->channel[0]->title[0] ;
				}
				if( isset($xml->item) )
				{
					foreach($xml->item as $item)
					{
						$i++;
						if( $i > $gmailmax ) break;
						$items[] = array('title' => $item->title[0] , 'link' => $item->link[0]);
					}
					$title = $xml->channel[0]->title[0] ;
				}
				else if ( isset($xml->entry) )
				{
					foreach($xml->entry as $entry)
					{
						$i++;
						if( $i > $gmailmax ) break;
						$items[] = array('title' => $entry->title[0] , 'link' => "http://mail.google.com/mail");
					}
					
					$title = $xml->title[0] ;
				}
				
				$this->assign("title", $title);
				$this->assign("items", $items);
			}
			else
			{
				$this->assign("title", "Failed to Load URL");
			}
		}
		else
		{
			$this->assign("title", "News Feed Reader");
		}
	}
}

?>