<?php 
/**
 * @copyright 2005 Charles Anssens
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
class DdayConfig extends Model
{
    public function build()
    {
	if ($this->permission >= _ADMIN_)
		{
			$this->assign("admin", true);
		}
    }
}

?>