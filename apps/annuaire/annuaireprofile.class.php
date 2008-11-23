<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

ClassLoader::add('ProfileFactory', dirname(__FILE__).'/classes/profilefactory.class.php');
ClassLoader::add('Profile', dirname(__FILE__).'/classes/profile.class.php');

/**
 * Display profile
 * 
 * @package applications
 */
class AnnuaireProfile extends Model
{
	function build()
	{
	
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$this->assign("image_width", $config["image"]["width"]);
		$this->assign("image_height", $config["image"]["height"]);
		$this->assign("image_weight", $config["image"]["weight"]);
	
		$username = $this->args['username'];
		$this->assign("username", $username );
		
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "profile") );
		
		$factory = new ProfileFactory($this->db, $GLOBALS['config']['bdd']["frameworkdb"].".profile");
		if( $profile = $factory->fetchFromUsername($username) )
		{
			$factory->fetchAddresses($profile);
			$factory->fetchPhones($profile);
			$factory->fetchEmails($profile);
			$this->assign("profile",  $profile->getProfile());
			$this->assign("addresses", $profile->getAddresses() );
			$this->assign("phones", $profile->getPhones() );
			$this->assign("emails", $profile->getEmails() );
		
			if( is_file(KARIBOU_PUB_DIR.'/profile_pictures/'.$profile->getId().".jpg") )
			{
				$this->assign("picture", "/pub/profile_pictures/".$profile->getId().".jpg");
			}
			else
			{
				$this->assign("picture", "/themes/default/images/0.jpg");
			}
		}
		$currentuser = $this->userFactory->getCurrentUser();
		if( ( ($this->permission >= _SELF_WRITE_)&&($username == $currentuser->getLogin()) )
			|| ($this->permission >= _FULL_WRITE_)
		 )
		{
			$this->assign('edit', true);
		}
		
		if( isset( $this->args['act'] ) && ($this->args['act']=='edit') )
		{
			$this->assign('addr_types', array("DOM", "INTL", "POSTAL", "HOME", "WORK") );
			$this->assign('phone_types', array("WORK", "HOME", "FAX", "CELL", "PAGER") );
			$this->assign('email_types', array("INTERNET", "AIM", "ICQ", "JABBER", "MSN", "SKYPE") );
		}

		$user = $this->userFactory->prepareUserFromLogin($username);		
		$this->assign("user", $user);


		//$groups = $this->userFactory->getGroups();
		//$this->assign('usergroups', $groups->getTree() );
		$user->getGroups($this->db);
		$userallgroups = $user->getAllGroups($this->db);
		$gkey = $GLOBALS['config']['geoloc']['gkey'];
		$this->assign('gkey', $gkey);
		$this->assign("usergroups", $userallgroups /*->getTree()*/);
		
		$this->assign('noflashmail', $GLOBALS['config']['noflashmail']);

		
		$currentUserGroups = $this->currentUser->getGroups($this->db);
		$groupsAdmin = array();
		foreach ($currentUserGroups as $group) {
			if ($group->role == 'admin') {
				$valid = true;
				foreach ($userallgroups as $userGroup) {
					if ($userGroup->getID() == $group->getID()) {
						$valid = false;
						break;
					}
				}
				if ($valid)
					$groupsAdmin[] = array('name' => $group->getName(), 'id' => $group->getID());
			}
		}
		$this->assign('currentUserAdmin', $groupsAdmin);
		$this->assign('currentUserId', $this->currentUser->getID());
	}
}

?>
