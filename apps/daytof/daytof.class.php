<?php 
/**
 * @copyright 2005 JoN
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
class DayTof extends Model
{
	public function build()
	{
		$this->assign("islogged", $this->currentUser->isLogged());
	}
}

?>
