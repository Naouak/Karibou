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
class KSDefault extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$app->addView("menu", "header_menu", array("page"=>"default"));
		
		$mySF = new KSurveyFactory ($this->db, $this->userFactory);
		
		$this->assign("surveys", $mySF->getSurveyList());
	}
}

?>
