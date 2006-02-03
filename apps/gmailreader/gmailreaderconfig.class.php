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
		$this->assign('gmaillogin', $this->userFactory->getCurrentUser()->getPref('gmaillogin'));
		$this->assign('gmailpass', $this->userFactory->getCurrentUser()->getPref('gmailpass'));
		$this->assign('gmailmax', $this->userFactory->getCurrentUser()->getPref('gmailmax'));
/*		
		if( isset($this->userFactory->getCurrentUser()->getPref('gmaillogin')) )
		{
			$this->assign('gmaillogin', $this->userFactory->getCurrentUser()->getPref('gmaillogin'));
		}
		if( isset($this->userFactory->getCurrentUser()->getPref('gmailpass')) )
		{
			$this->assign('gmailpass', $this->userFactory->getCurrentUser()->getPref('gmailpass'));
		}
		if( isset($this->userFactory->getCurrentUser()->getPref('gmailmax')) )
		{
			$this->assign('gmailmax', $this->userFactory->getCurrentUser()->getPref('gmailmax'));
		}
*/		
	}
}

?>