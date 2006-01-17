<?php 
/**
 * @version $Id:  gmailreader.class.php,v 0.1 2006/01/14 00:00:00 dat Exp $
 * @copyright 2006 Arnaud Cosson
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

		if( isset($gmaillogin, $gmailpass, $gmailmax) )
		{
			$rss_file = "https://".$gmaillogin.":".$gmailpass."@mail.google.com/mail/feed/atom";
			$rss_feed = new XMLCache(KARIBOU_CACHE_DIR.'/xml_rss');
			if( $rss_feed->loadURL($rss_file) )
			{
				$xml = $rss_feed->getXML();
				$title = "No suitable Feed found";
				$items = array();
				$i = 0;
				
				if ( isset($xml->entry) )
				{
					foreach($xml->entry as $entry)
					{
						$i++;
						if( $i > $gmailmax ) break;
						$author = $entry->author[0];
						$items[] = array('title' => $entry->title[0] , 'summary'=> $entry->summary[0], 'link' => "http://mail.google.com/mail", 'author' => $author->name[0], 'emailAuthor' => $author->email[0]);
					}
				}
				$title = $xml->title[0] ;
				$nbMessages = $xml-> fullcount[0];				
				$this->assign("title", $title);
				$this->assign("nbMessages", $nbMessages);
				$this->assign("items", $items);
			}
			else
			{
				$this->assign("title", "Failed to Load URL");
			}
		}
		else
		{
			$this->assign("title", "Gmail Reader");
		}
	}
}

?>