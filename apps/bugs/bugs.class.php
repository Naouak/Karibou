<?php
/**
 * @copyright Grégoire Leroy  <lupuscramus@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/


/**
 *
 * @package applications
 */

class Bugs extends Model
{
	public function build()
	{
		$this->assign("isadmin", $this->getPermission() == _ADMIN_);
	}
}
 
