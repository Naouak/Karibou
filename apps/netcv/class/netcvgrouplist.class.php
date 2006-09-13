<?php
//Classe gerant les groupes de CV de l'utilisateur
class NetCVGroupList {
	public $cvGroups;
	public $cvGroupsObjects;
	public $infos;
	public $user_id;
	public $db;

	function __construct ($db, $user_id) {
		$this->db = $db;
		//$this->cvgroup = array();
		$this->user_id = $user_id;

		//Ajouter les infos du groupe dans $infos
		$groupSelectReq = "
			SELECT g.*, s.filename as skin_filename
			FROM netcv_resumes_group g, netcv_skins s
			WHERE user_id = '".$user_id."'
			AND s.id = g.skin_id";
		$groupSelectRes = $this->db->prepare($groupSelectReq);
		$groupSelectRes->execute();

		if ( $groupSelectRows = $groupSelectRes->fetchAll(PDO::FETCH_ASSOC)) {
			foreach($groupSelectRows as $cvGroup) {
				//$this->cvGroups[] = $cvGroup;
				$this->cvGroupsObjects[] = new NetCVGroup($this->db, $cvGroup);
				//$this->cvGroupsObjects[] = $this->returnCVObjects($cvGroup["group_id"]);
				
			}
		} else {
		  $this->infos = FALSE;
		}
		
	}

	function returnGroupsObjects () {
		return $this->cvGroupsObjects;
	}
	
	function returnGroupById ($id) {
		if ( (count($this->cvGroupsObjects) > 0) && ($id != "")) {
	   		foreach($this->cvGroupsObjects as $cvGroup) {
	   			if ($cvGroup->getInfo("id") == $id) {
		   			return $cvGroup;
	   			}
	   		}
	   	} else {
	   		return FALSE;
	   	}
	   	return FALSE;
	}
	function updateInfos ($group_id,$infos) {
		$elementsReq = "";
		foreach ($infos as $key => $info) {
			$elementsReq .= "`".$key."` = '".$info."', " ;
		}
	
	
		$cvGroupInsertReq = "
			UPDATE netcv_resumes_group
			SET
			".$elementsReq."
			date_modification = '".date("Y-m-d H:i:s")."'
			WHERE id = '".$group_id."' AND user_id = '".$this->user_id."'";

		$cvGroupInsertRes = $this->db->prepare($cvGroupInsertReq);
		$cvGroupInsertRes->execute();
	}
	
	function getInfo($key) {
		if (isset($this->infos[$key])) {
			return $this->infos[$key];
		}
	}
	
	function insertGroup($infos) {
		$keysReq = "";
		$valuesReq = "";
		foreach ($infos as $key => $info) {
			$keysReq .= ",".$key ;
			$valuesReq .= ",'".$info."'";
		}
	
			$cvGroupInsertReq = "
			INSERT INTO netcv_resumes_group
			(user_id".$keysReq.",date_creation,date_modification)
			VALUES ('".$this->user_id."'".$valuesReq.",'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";

			$cvGroupInsertRes = $this->db->prepare($cvGroupInsertReq);
			$cvGroupInsertRes->execute();
			
			return $this->db->lastInsertId();
	}
	
	function deleteGroup ($group_id) {
		//Verification que le group_id propose appartient bien au user		
		$userHasGroup = FALSE;
		$cvGroupSelectReq = "SELECT count(*) as gcount FROM netcv_resumes_group WHERE id = '".$group_id."' AND user_id = '".$this->user_id."'";
		$cvGroupSelectRes = $this->db->prepare($cvGroupSelectReq);
		$cvGroupSelectRes->execute();
		$cvsDeleteReq = "";
		if ( $cvGroupSelectRows = $cvGroupSelectRes->fetchAll(PDO::FETCH_ASSOC)) {
			if ($cvGroupSelectRows[0]["gcount"] > 0) {
				$userHasGroup = TRUE;
			}
		}
		
		if ($userHasGroup) {
			$cvSelectSingleCVReq = "SELECT id FROM netcv_resumes_by_lang WHERE group_id = '".$group_id."'";
			$cvSelectSingleCVRes = $this->db->prepare($cvSelectSingleCVReq);
			$cvSelectSingleCVRes->execute();
			$cvsDeleteReq = "";
			if ( $cvSelectSingleCVRows = $cvSelectSingleCVRes->fetchAll(PDO::FETCH_ASSOC)) {
				$first = TRUE;
				foreach($cvSelectSingleCVRows as $cvSelectSingleCVRow) {
					if ($first) {
						$cvsDeleteReq .= "
							DELETE FROM netcv_resumes
							WHERE resume_id = '".$cvSelectSingleCVRow["id"]."'";
					} else {
						$cvsDeleteReq .= " OR resume_id = '".$cvSelectSingleCVRow["id"]."'";
					}
					$first = FALSE;
				}
	
				$cvsDeleteRes = $this->db->prepare($cvsDeleteReq);
				$cvsDeleteRes->execute();
			} else {
				//Aucun CV dans ce groupe
			}
			
			$cvSingleCVDeleteReq = "
				DELETE FROM netcv_resumes_by_lang
				WHERE group_id = '".$group_id."'";
			$cvSingleCVDeleteRes = $this->db->prepare($cvSingleCVDeleteReq);
			$cvSingleCVDeleteRes->execute();
			
			$cvGroupDeleteReq = "
				DELETE FROM netcv_resumes_group
				WHERE id = '".$group_id."' AND user_id = '".$this->user_id."'";
			$cvGroupDeleteRes = $this->db->prepare($cvGroupDeleteReq);
			$cvGroupDeleteRes->execute();
		}
	}
	
}

?>