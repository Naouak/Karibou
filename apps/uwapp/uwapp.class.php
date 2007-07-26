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
class uwApp extends Model
{
	public function build()
	{
		if( isset($this->args['title'], $this->args['url']) )
		{
			$this->assign("title", $this->args['title']);
			$this->assign("url", $this->args['url']);
		}
		else
		{
			$this->assign("title", "UWA Module");
		}
	}
}

?>
