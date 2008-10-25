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
class gmailReaderSaveConfig  extends FormModel
{
	public function build()
	{
		$currentUser = $this->userFactory->getCurrentUser();
		$keychain = KeyChainFactory::getKeyChain($currentUser);
		$keychain->unlock();
		$keychain->set('gmaillogin', $this->args['gmaillogin']);
		$keychain->set('gmailpass', $this->args['gmailpass']);
		$keychain->set('gmailmax', $this->args['gmailmax']);
		$this->setRedirectArg('page', '');
	}
}

?>