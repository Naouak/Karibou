<?php 
/**
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
class DVDefault extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		
		$myDV = new KDataView($this->db, $this->userFactory);
		
		foreach ($config["sources"] as $tablename => $source)
		{
			$myDV->addSource($tablename, $source);
		}
		
		$this->assign("sources", $myDV->sources);
	}
}

?>
