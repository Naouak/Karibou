<?php
//Classe gerant un CV
class NetCVSingleCVList {
	public $infos;
	public $CVList;

	function __construct($db, $group_id) {
		$this->db = $db;

		//Ajouter les infos du groupe dans $infos
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
	   			if ($cv->getInfo("default") == '1') {
		   			return $cv;
	   			}
	   		}
	   	} else {
	   		return FALSE;
	   	}
	   	return FALSE;
	}	

}
?>