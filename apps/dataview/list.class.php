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
			
			if (isset($this->args["pagenum"]) && $this->args["pagenum"] != "")
			{
				$pagenum = $this->args["pagenum"];
			}
			else
			{
				$pagenum = 1;
			}
			$this->assign("pagenum", $pagenum);
			$this->assign("maxlines", $config["list"]["maxlines"]);
			
			$this->assign("nbrecords", $mySource->countRecords());
			
			$myRecords = $mySource->getRecords($config["list"]["maxlines"], $pagenum);
			$this->assign("records", $myRecords );
			
			$this->assign("publicfields", $config["sources"][$this->args["source"]]["public"] );
		}
		else
		{
			Debug::kill($this->args["source"]);
		}
	}
}

?>