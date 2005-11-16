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
class MyNotes extends Model
{
	public function build()
	{
		if(isset($this->args['notes']))
			$this->assign('notes', $this->args['notes']);
	}
}

?>