<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information (GPL).
 * 
 * @package daemons
 **/

ClassLoader::add('ProfileFactory', KARIBOU_APP_DIR.'/annuaire/classes/profilefactory.class.php');
ClassLoader::add('Profile', KARIBOU_APP_DIR.'/annuaire/classes/profile.class.php');

/**
 * @package daemons
 */
class LoginListener extends Listener
{
	function eventOccured(Event $event)
	{
		$currentUser = $this->userFactory->getCurrentUser();

		$factory = new ProfileFactory($this->db, $GLOBALS['config']['bdd']["annuairedb"].".profile");
		if( $profile = $factory->fetchFromUsername($currentUser->getLogin()) )
		{
			$factory->fetchEmails($profile);
			$emails = $profile->getEmails();
			if (count ($emails) < 2)
			{
				$this->messageManager->addMessage("default", "NOEMAIL");
			}

            if ($currentUser->getPref('changePassword') === FALSE)
            {
                //No changepassword pref set
				$this->messageManager->addMessage("default", "CHANGEPASSWORD");
            }
            elseif ($currentUser->getPref('changePassword') == 0)
            {
                //No need to change password
            }
            elseif ($currentUser->getPref('changePassword') == 1)
            {
                //User must change password
				$this->messageManager->addMessage("default", "CHANGEPASSWORD");
            }
		}
	}
}

?>
