<?php
//Classe gerant un CV
class NetCVSingleCVList {
	public $infos;
	public $CVList;

	function __construct($db, $group_id) {
		$this->db = $db;

		//Ajouter les infos du groupe dans $infos
		
		/*
		Il faudrait permettre de récupérer le nombre de sections et d'élément pour valider la quantité d'informations du CV
		La requête suivante renvoie le nombre de sections + 1
		
		SELECT * , count( netcv_resumes.id )-1 AS countSections
		FROM netcv_resumes_by_lang
		LEFT JOIN netcv_resumes ON netcv_resumes.resume_id = netcv_resumes_by_lang.id
		WHERE netcv_resumes_by_lang.group_id = '1'
		AND netcv_resumes.resume_id = '1'
		AND netcv_resumes.parent_id = '0'
		GROUP BY netcv_resumes.resume_id
		*/
		
		$cvSelectReq = "SELECT * FROM netcv_resumes_by_lang WHERE group_id = '".$group_id."'";
		$cvSelectRes = $this->db->prepare($cvSelectReq);
		$cvSelectRes->execute();

		if ( $cvSelectRows = $cvSelectRes->fetchAll(PDO::FETCH_ASSOC)) {
			foreach($cvSelectRows as $cvSelectRow){
				$this->CVList[] = new NetCVSingleCV($this->db, $cvSelectRow);
			}
		} else {
		  $this->infos = FALSE;
		}
	}

	function getInfo($key) {
		if (isset($this->infos[$key])) {
			return $this->infos[$key];
		} else {
			return FALSE;
		}
	}
	
	function returnCVList () {
		return $this->CVList;
	}
	
	function returnCVById ($id) {
		if ( (count($this->CVList) > 0) && ($id != "")) {
	   		foreach($this->CVList as $cv) {
	   			if ($cv->getInfo("id") == $id) {
		   			return $cv;
	   			}
	   		}
	   	} else {
	   		return FALSE;
	   	}
	   	return FALSE;
	}
	
	function returnCVByLang ($lang) {
		if ( (count($this->CVList) > 0) && ($lang != "")) {
	   		foreach($this->CVList as $cv) {
	   			if ($cv->getInfo("lang") == $lang) {
		   			return $cv;
	   			}
	   		}
	   	} else {
	   		return FALSE;
	   	}
	   	return FALSE;
	}
	
	function returnDefaultCV () {
		if ( (count($this->CVList) > 0)) {
	   		foreach($this->CVList as $cv) {
	   			//if ($cv->getInfo("default") == '1') {
		   			return $cv;
	   			//}
	   		}
	   	} else {
	   		return FALSE;
	   	}
	   	return FALSE;
	}	

}
?>