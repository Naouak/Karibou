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
		$sort = $this->args['sort'];
		
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

		$sql = $this->db->prepare("SELECT * FROM bugs_bugs ORDER BY $sort $order LIMIT $start , $end");
		$stmt = $this->db->prepare("SELECT * FROM bugs_module ORDER BY id");
		
				
		try {
			$sql->execute();
			$stmt->execute();

			$modules = $stmt->fetchAll();
			$bugs = array();
			//echo("<br /><br /><br />");
			
			while($bugRow = $sql->fetch(PDO::FETCH_ASSOC))
			{
				if ($user["object"] =  $this->userFactory->prepareUserFromId($bugRow['reporter_id']))
					$bug['author']=$user;
					$bug['id']=$bugRow['id'];
					$bug['summary']=$bugRow['summary'];
					$bug['bug']=$bugRow['bug'];
					$bug['browser']=$bugRow['browser'];
					$bug['module_id']=$bugRow['module_id'];
					$bug['doublon_id']=$bugRow['doublon_id'];
					$bug['state']=$bugRow['state'];
					$bug['type']=$bugRow['type'];
					$id = $bugRow['module_id'] - 1;
					$bug['module']=$modules[$id]['name'];
					$bug['reporter']=$bugRow['reporter_id'];
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

			/*echo("<br /><br /><br />");
			print_r($bugs);
			print_r($modules);*/
			
		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}
		
	}
}