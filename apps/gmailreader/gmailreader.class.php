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
	
		if (
			isset($this->args['gmaillogin'], $this->args['gmailpass'], $this->args['gmailmax'])
			&& $this->args['gmaillogin'] != "" && $this->args['gmailpass'] != "" && $this->args['gmailmax'] != ""
			)
		{
			$currentUser = $this->userFactory->getCurrentUser();
			$prefName = 'gmaillogin';
			$currentUser->setPref($prefName, $this->args['gmaillogin']);
			$prefName = 'gmailpass';
			$currentUser->setPref($prefName, $this->args['gmailpass']);
			$prefName = 'gmailmax';
			$currentUser->setPref($prefName, $this->args['gmailmax']);				
		}
//		else
//		{
			$gmaillogin = $this->userFactory->getCurrentUser()->getPref('gmaillogin');
			$gmailpass = $this->userFactory->getCurrentUser()->getPref('gmailpass');
			$gmailmax = $this->userFactory->getCurrentUser()->getPref('gmailmax');
//		}

		if( isset($gmaillogin, $gmailpass, $gmailmax) && $gmaillogin !== FALSE && $gmailpass !== FALSE && $gmailmax !== FALSE)
		{
			$this->assign("config", true);
			$rss_file = "https://".$gmaillogin.":".$gmailpass."@mail.google.com/mail/feed/atom";
			$rss_feed = new XMLCache(KARIBOU_CACHE_DIR.'/xml_rss');
			if( $rss_feed->loadURL($rss_file,25) )
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
						$items[] = array('title' => $entry->title[0] , 'summary'=> $entry->summary[0], 'link' => "http://mail.google.com/mail", 'author' => $author->name[0], 'emailAuthor' => $author->email[0], 'issued' => $this->parseDate($entry->issued[0]->text) );
					}
				}
				$title = $xml->title[0] ;
				$nbMessages = $xml-> fullcount[0];

				if ($title != NULL)
				{
					$this->assign("title", $title);
					$this->assign("nbMessages", $nbMessages->__toInt());
					$this->assign("items", $items);
				}
				else
				{
					$this->assign("title", "Failed");
				}
			}
			else
			{
				$this->assign("title", "Failed");
			}
		}
		else
		{
			$this->assign("title", "Gmail Reader");
			$this->assign("config", false);
		}
	}
	
	public function parseDate($gdate)
	{
		preg_match("/([0-9]+)-([0-9]+)-([0-9]+)[A-Z]+([0-9]+):([0-9]+):([0-9]+)[A-Z]/", $gdate, $array);
		$date = date("U", mktime ($array[4], $array[5], $array[6], $array[2], $array[3], $array[1]));
		//$date = mktime ($array[4], $array[5], $array[6], $array[2], $array[3], $array[1]);
		return (time() - $date);
		
	}
}

?>
