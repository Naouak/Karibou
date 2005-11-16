<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 
class CreateAccount extends Model
{
	public function build()
	{
		if (isset($GLOBALS['config']['login']['allowaccountcreation'])) {
			$this->assign('allowaccountcreation', $GLOBALS['config']['login']['allowaccountcreation']);
		} else {
			$this->assign('allowaccountcreation', TRUE);
		}
	}
}

?>