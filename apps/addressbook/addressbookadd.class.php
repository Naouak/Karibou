<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

ClassLoader::add('ProfileFactory', dirname(__FILE__).'/../annauire/classes/profilefactory.class.php');
ClassLoader::add('Profile', dirname(__FILE__).'/../annuaire/classes/profile.class.php');

/**
 * Display profile
 * 
 * @package applications
 */
class AddressBookAdd extends Model
{
	function build()
	{
		$this->assign('addr_types', array("DOM", "INTL", "POSTAL", "HOME", "WORK") );
		$this->assign('phone_types', array("WORK", "HOME", "FAX", "CELL", "PAGER") );
		$this->assign('email_types', array("INTERNET", "AIM", "ICQ", "JABBER", "MSN", "SKYPE") );
	}
}

?>