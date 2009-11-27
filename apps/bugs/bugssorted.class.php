<?php

/**
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/ 

class BugsSorted extends Model
{
	public function build()
	{
		if( $this->args['sort'] == "state" || $this->args['sort'] == "type" || $this->args['sort'] == "summary" ) {
			$sort = "b.".$this->args['sort'];
		} else {
			$sort = "b.module_id";
		}
		
		
		if($this->args['ascdescsort'] == 2)
			$order = "DESC";
		else
			$order = "ASC";

		$page = $this->args['numberpage'];
		if($page==1) {
			$start = 0;
			$end = 30;
		} else {
			$start = 30 * ($page - 1);
			$end = $start + 30;
		}

		$sql = $this->db->prepare("SELECT b.id, b.summary, b.module_id, b.state, b.type, bugs_module.name FROM bugs_bugs as b LEFT JOIN bugs_module ON bugs_module.id = b.module_id ORDER BY $sort $order LIMIT $start , $end");
		
				
		try {
			$sql->execute();
			$bugs = array();
			
			while($bugRow = $sql->fetch(PDO::FETCH_ASSOC))
			{
				if ($user["object"] =  $this->userFactory->prepareUserFromId($bugRow['id']))
					$bug['id']=$bugRow['id'];
					$bug['summary']=$bugRow['summary'];
					$bug['module_id']=$bugRow['module_id'];
					$bug['state']=$bugRow['state'];
					$bug['type']=$bugRow['type'];
					$bug['module']=$bugRow['name'];
					$bugs[] = $bug;
			}

			if($bugs[29] !== null)
				$next = 1;
			else
				$next = 0;

			if($page == 1)
				$previous = 0;
			else
				$previous = 1;

			$this->assign("numberpage",$page);
			$this->assign("nextpage",$page+1);
			$this->assign("previouspage",$page-1);
			$this->assign("previous",$previous);
			$this->assign("next",$next);
			$this->assign("bugs",$bugs);
			$this->assign("sort",$sort);
			$this->assign("order",$order);

		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}
		
	}
}