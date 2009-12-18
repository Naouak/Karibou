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
		//Filtrage de POST (pour la recherche)
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

		//Par défaut, on trie par état.
		if( $this->args['sort'] == "module_id" || $this->args['sort'] == "type" || $this->args['sort'] == "summary") {
			$sort = "b.".$this->args['sort'];
		} else {
			$sort = "b.state";
		}
		
		//Par défaut, on trie par ordre croissant
		if($this->args['ascdescsort'] == 2)
			$order = "DESC";
		else
			$order = "ASC";

		//En fonction du numéro de page, on ne met pas les mêmes éléments (pagination).
		$page = $this->args['numberpage'];
		if($page==1) {
			$start = 0;
		} else {
			$start = 30 * ($page - 1);
		}

		//Première partie de la requête : ce qu'on sélectionne
		$sql1 = "SELECT
						b.id, b.summary AS summary, b.module_id AS module_id, b.state AS state, b.type AS type, bugs_module.name
				FROM
						bugs_bugs as b
				LEFT JOIN
						bugs_module
				ON
						bugs_module.id = b.module_id";

		//Deuxième partie de la requête : le filtre (where) ne se fait que lorsque l'user a lancé une recherche, i.e que POST est rempli.
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
		$sql1.="ORDER BY $sort $order LIMIT $start , 30";

		$sql = $this->db->prepare($sql1);

		$stmt = $this->db->prepare("
			SELECT
				*
			FROM
				bugs_module
		");
				
		try {
			$sql->execute();
			$stmt->execute();

			$modules = $stmt->fetchAll();
			$bugs = $sql->fetchAll();
			
			$next = 0;
			$previous = 1;
			if($bugs[29] !== null)
				$next = 1;
			if($page == 1)
				$previous = 0;

			$this->assign("previouspage",$page-1);
			$this->assign("nextpage",$page+1);
			$this->assign("numberpage",$page);
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