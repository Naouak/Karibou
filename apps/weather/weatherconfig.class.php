<?php 
/**
 * @copyright 2008 SwaEn http://lodewijk.boutu.netcv.fr
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

class weatherConfig extends Model
{
	public function build()
	{
		if( isset($this->args['city']) )
		{
			$this->assign('city', $this->args['city']);
		}
	}
}

?>
