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
class DVList extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		
		$myDV = new KDataView($this->db, $this->userFactory);
		
		if ( isset($this->args["source"]) && $this->args["source"] != "" && isset($config["sources"][$this->args["source"]]) )
		{
			$myDV->addSource($this->args["source"], $config["sources"][$this->args["source"]]);

			$mySource = $myDV->getSource($this->args["source"]);
			$this->assign("source", $mySource );
			
			$myRecords = $mySource->getRecords();
			$this->assign("records", $myRecords );
		}
		else
		{
			Debug::kill($this->args["source"]);
		}
	}
}

?>
