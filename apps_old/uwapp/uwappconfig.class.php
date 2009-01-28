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
class uwAppConfig extends Model
{
	public function build()
	{
		if( isset($this->args['title']) )
		{
			$this->assign('title', $this->args['title']);
		}
		if( isset($this->args['url']) )
		{
			$this->assign('url', $this->args['url']);
		}
	}
}

?>
