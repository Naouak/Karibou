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
class AddressBookProfile extends Model
{
	function build()
	{
		$profile_id = $this->args['profile_id'];
		
		$factory = new ProfileFactory($this->db, "addressbook");
		$profile = $factory->fetchFromId($profile_id);
		$factory->fetchAddresses($profile);
		$factory->fetchPhones($profile);
		$factory->fetchEmails($profile);
		
		if( is_file(KARIBOU_PUB_DIR.'/profile_pictures/'.$profile->getId().".jpg") )
		{
			$this->assign("picture", "/pub/profile_pictures/".$profile->getId().".jpg");
		}
		else
		{
			$this->assign("picture", "/themes/default/images/0.jpg");
		}
		$this->assign("profile",  $profile->getProfile() );
		$this->assign("addresses", $profile->getAddresses() );
		$this->assign("phones", $profile->getPhones() );
		$this->assign("emails", $profile->getEmails() );
		
		$currentuser = $this->userFactory->getCurrentUser();
		if( ( ($this->permission >= _SELF_WRITE_) )
			|| ($this->permission >= _FULL_WRITE_)
		 )
		{
			$this->assign('edit', true);
			$this->assign('profile_id', $profile_id);
		}
		
		if( isset( $this->args['act'] ) && ($this->args['act']=='edit') )
		{
			$this->assign('addr_types', array("DOM", "INTL", "POSTAL", "HOME", "WORK") );
			$this->assign('phone_types', array("WORK", "HOME", "FAX", "CELL", "PAGER") );
			$this->assign('email_types', array("INTERNET", "AIM", "ICQ", "JABBER", "MSN", "SKYPE") );
		}
	}
}

?>