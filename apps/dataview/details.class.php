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
class DVDetails extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		
		$myDV = new KDataView($this->db, $this->userFactory);
		
		if ( isset($this->args["source"], $this->args["recordid"]) && $this->args["source"] != "" && isset($config["sources"][$this->args["source"]]) && $this->args["recordid"] )
		{
			$recordid = $this->args["recordid"];
		
			$myDV->addSource($this->args["source"], $config["sources"][$this->args["source"]]);

			$mySource = $myDV->getSource($this->args["source"]);
			$this->assign("source", $mySource );

			$myRecord = $mySource->getRecordById($recordid);
			$this->assign("record", $myRecord );
			
			$this->assign("publicfields", $config["sources"][$this->args["source"]]["public"] );
			$this->assign("privatefields", $config["sources"][$this->args["source"]]["private"] );
		}
		else
		{
			Debug::kill($this->args["source"]);
		}
	}
}

?>
