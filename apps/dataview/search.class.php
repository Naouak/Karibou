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
class DVSearch extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
	
		$myDV = new KDataView($this->db, $this->userFactory);
		
		if ( isset($this->args["source"]) && $this->args["source"] != "" && isset($config["sources"][$this->args["source"]]) )
		{
			$app->addView("menu", "header_menu", array("page"=>"search", "source" => $this->args["source"]));
		
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

			
			$this->assign("publicfields", $config["sources"][$this->args["source"]]["public"] );
			$this->assign("listfields", $config["sources"][$this->args["source"]]["list"] );
			
			$allfields = array_merge ($config["sources"][$this->args["source"]]["public"], $config["sources"][$this->args["source"]]["private"]);
			
			if (isset($_POST["keyword"]) && trim($_POST["keyword"]) != "")
			{
				$keyword = $_POST["keyword"];
			}
			elseif (isset($this->args["keyword"]) && $this->args["keyword"] != "")
			{
				$keyword = $this->args["keyword"];
			}

			if (isset($keyword))
			{
				$this->assign("nbrecords", $mySource->countRecords($keyword, $allfields));
				$records = $mySource->getRecordsFromSearch($keyword, $allfields, $config["list"]["maxlines"], $pagenum);
				$this->assign("records", $records);
				$this->assign("keyword", $keyword);
			}
		}
		else
		{
			Debug::kill($this->args["source"]);
		}
	}
}

?>