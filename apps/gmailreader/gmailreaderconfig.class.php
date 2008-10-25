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
class gmailReaderConfig  extends Model
{
	public function build()
	{
		$keychain = KeyChainFactory::getKeyChain($this->userFactory->getCurrentUser());
		$this->assign('gmaillogin', $keychain->get('gmaillogin'));
		$this->assign('gmailpass', $keychain->get('gmailpass'));
		$this->assign('gmailmax', $keychain->get('gmailmax'));
	}
}

?>