<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

ClassLoader::add('ProfileFactory', dirname(__FILE__).'/../annuaire/classes/profilefactory.class.php');
ClassLoader::add('Profile', dirname(__FILE__).'/../annuaire/classes/profile.class.php');

/**
 * Display profile
 * 
 * @package applications
 */
class AddressBookPost extends FormModel
{
	function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
	
		$profile = array();
		$addresses = array();
		$address_args = 'type|poaddress|extaddress|street|city|region|postcode|country|delete';
		$phones = array();
		$phones_args = 'type|number|delete';
		$emails = array();
		$emails_args = 'type|email|delete';
		
		foreach($_POST as $name => $value)
		{
			if( preg_match('/address([0-9]+)_('.$address_args.')/', $name, $match) )
			{
				if( !isset($addresses[$match[1]]) ) $addresses[$match[1]] = array();
				$addresses[$match[1]][$match[2]] = $value;
			}
			else if( preg_match('/phone([0-9]+)_('.$phones_args.')/', $name, $match) )
			{
				if( !isset($phones[$match[1]]) ) $phones[$match[1]] = array();
				$phones[$match[1]][$match[2]] = $value;
			}
			else if( preg_match('/email([0-9]+)_('.$emails_args.')/', $name, $match) )
			{
				if( !isset($emails[$match[1]]) ) $emails[$match[1]] = array();
				$emails[$match[1]][$match[2]] = $value;
			}
			else
			{
				$profile[$name] = $value;
			}
		}
		$addresses_tab = array();
		foreach( $addresses as $a )
		{
			if( !isset($a['delete']) ) $addresses_tab[] = $a;
		}
		$phones_tab = array();
		foreach( $phones as $a )
		{
			if( !isset($a['delete']) ) $phones_tab[] = $a;
		}
		$emails_tab = array();
		foreach( $emails as $a )
		{
			if( !isset($a['delete']) ) $emails_tab[] = $a;
		}
		
		$factory = new ProfileFactory($this->db, "addressbook");
		if( isset($_POST['profile_id']) )
		{
			if ($p = $factory->fetchFromId($_POST['profile_id']))
			{
				$factory->updateAll($p, $profile, $addresses_tab, $phones_tab, $emails_tab);
			}
			else
			{
				Debug::kill("No profile for this ID");
			}
		}
		else
		{
			$p = $factory->insertProfile($profile);
			try
			{
				$this->db->exec("INSERT INTO addressbook_user (user_id, profile_id) VALUES (".$this->currentUser->getID()." , ".$p->getId().") ");
			}
			catch(PDOException $e)
			{
				Debug::kill( $e->getMessage() );
			}

			$factory->insertAll($p, $addresses_tab, $phones_tab, $emails_tab);

		}
		
		//NetJobs : Linking contact to job (if exists) & company
		if (isset($_POST["netjobs"]) && $_POST["netjobs"] == 1)
		{
			$sql = "";		
			if (isset($_POST["jobid"]) && $_POST["jobid"] != "")
			{
					$sql .= "INSERT INTO netjobs_contacts (`contact_id`, `type`, `id`, `datetime`) VALUES (".$p->getId().", 'job', '".$_POST["jobid"]."', NOW() ) ON DUPLICATE KEY UPDATE `datetime` = NOW(); ";
			}
			
			if (isset($_POST["companyid"]) && $_POST["companyid"] != "")
			{
					$sql .= "INSERT INTO netjobs_contacts (`contact_id`, `type`, `id`, `datetime`) VALUES (".$p->getId().", 'company', '".$_POST["companyid"]."', NOW() ) ON DUPLICATE KEY UPDATE `datetime` = NOW(); ";
			}
			else
			{
				Debug::kill("No CompanyID!");
			}
			
			try
			{
				$stmt = $this->db->exec($sql);
				
			}
			catch(PDOException $e)
			{
				Debug::kill( $e->getMessage() );
			}


		
			$this->setRedirectArg("app", "netjobs" );
			if (isset($_POST["jobid"]) && $_POST["jobid"] != "")
			{
				$this->setRedirectArg("page", "locationedit" );
				$this->setRedirectArg("jobid", $_POST["jobid"] );
			}
			elseif (isset($_POST['profile_id']))
			{
				$this->setRedirectArg("page", "contactdetails" );
				$this->setRedirectArg("contactid", $_POST['profile_id'] );
			}
			else
			{
				Debug::kill("No JobID or ProfileID!");
			}
		}
		else
		{
			$this->setRedirectArg("profile_id", $p->getId() );
		}

		/* Dealing with picture upload */
		if( isset($_FILES['picture']) && !empty($_FILES['picture']['name'])  )
		{
			switch( strtolower($_FILES['picture']['type']) )
			{
				case 'image/png':
					$im = imagecreatefrompng($_FILES['picture']['tmp_name']);
					break;
				case 'image/pjpeg':
					$im = imagecreatefromjpeg($_FILES['picture']['tmp_name']);
					break;
				case 'image/jpeg':
					$im = imagecreatefromjpeg($_FILES['picture']['tmp_name']);
					break;
				case 'image/gif':
					$im = imagecreatefromgif($_FILES['picture']['tmp_name']);
					break;
				default:
					$im = false;
					break;
			}
			if($im)
			{
				$x = imagesx($im);
				$y = imagesy($im);
				
				$x_ratio = $config["image"]["width"] / $x;
				$y_ratio = $config["image"]["height"] / $y;
				
				if( $x_ratio > $x_ratio )
				{
					$new_x = $x * $y_ratio;
					$new_y = $y * $y_ratio;
				}
				else
				{
					$new_x = $x * $x_ratio;
					$new_y = $y * $x_ratio;
				}
				$new_im = imagecreatetruecolor($new_x, $new_y);
				imagecopyresampled($new_im, $im, 0, 0, 0, 0, $new_x, $new_y, $x, $y);
				imagedestroy($im);
				$profile = $p->getProfile() ;
				if( !is_dir(KARIBOU_PUB_DIR.'/profile_pictures') ) mkdir(KARIBOU_PUB_DIR.'/profile_pictures');
				imagejpeg($new_im, KARIBOU_PUB_DIR.'/profile_pictures/'.$profile['id'].'.jpg', 60);
				imagedestroy($new_im);
			}
		}

	}
}

?>
