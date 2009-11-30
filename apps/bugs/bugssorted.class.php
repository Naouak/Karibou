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
		$post = array (
			'module_id' => array (
				'filter' => FILTER_SANITIZE_NUMBER_INT,
				'flags'  => FILTER_REQUIRE_ARRAY,
			),
			'state' => array (
				'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
				'flags' => FILTER_REQUIRE_ARRAY,
			),
			'type' => array (
				'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
				'flags' => FILTER_REQUIRE_ARRAY,
			)
		);
		
		$filter = filter_input_array(INPUT_POST, $post);
		
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

		$sql1 = "SELECT b.id, b.summary AS summary, b.module_id AS module_id, b.state AS state, b.type AS type, bugs_module.name FROM bugs_bugs as b LEFT JOIN bugs_module ON bugs_module.id = b.module_id";

		$conds = array();
		foreach($filter as $key => $value) {
			$conds2 = array();
			foreach($value as $value2) {
				$conds2[] = "$key = " . $this->db->quote($value2);
			}
			if(!empty($conds2)) $conds[] = "(" . implode(" OR ", $conds2) . ")";
		}
		if(!empty($conds)) {
			$where = " WHERE " . implode(" AND ", $conds) . " ";
		} else {
			$where = " ";
		}

		$sql1.= $where;
		$sql1.="ORDER BY $sort $order LIMIT $start , $end";

		$sql = $this->db->prepare($sql1);
		$stmt = $this->db->prepare("SELECT * FROM bugs_module");
				
		try {
			$sql->execute();
			$stmt->execute();

			$modules = $stmt->fetchAll();
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
			$next = 0;
			$previous = 0;
			if($bugs[29] !== null)
				$next = 1;
			if($page == 1)
				$previous = 0;

			$this->assign("numberpage",$page);
			$this->assign("nextpage",$page+1);
			$this->assign("previouspage",$page-1);
			$this->assign("previous",$previous);
			$this->assign("next",$next);
			$this->assign("bugs",$bugs);
			$this->assign("sort",$sort);
			$this->assign("order",$order);
			$this->assign("modules",$modules);

		} catch (PDOException $e) {
			Debug::kill($e->getMessage());
		}
		
	}
}