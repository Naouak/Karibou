<?php

define("NETCV_MIN_INFOS", 6);
define("NETCV_MIN_SECTIONS", 6);
define("NETCV_MIN_ELEMENTS", 6);

class NetCVSingleCV {
	public $infos;
	public $content;
	protected $languages;
	

	function __construct($db, $infos = FALSE) {
			$this->db = $db;
			if ($infos != FALSE) {
				$this->infos = $infos;
			} else {
				//On va charger le SingleCV par la suite
			}
	}
	
	function getInfo($key) {
		if (isset($this->infos[$key])) {
			return $this->infos[$key];
		} else {
			return FALSE;
		}
	}
	//Methode retournant le contenu du CV
	function getContent() {
	  if (isset($this->content)) {
			//Normalement le contenu est charge
	  } else {
	  	//On charge le contenu
			$this->content = new NetCVContent($this->db, $this->getInfo('id')/*$this->getInfo("resumes")*/);
			$this->content->getChildSQL();
			
	  }
	  
		return $this->content;
	}
		
	/*
	function getContent (/*$id/) {
			$this->content = new NetCVResume($this->db, $id/*$this->getInfo("resumes")/);
			$this->content->getChildSQL();
	}
	*/
	
	function load ($userid, $groupid, $cvid) {
		if ($groupid == FALSE) {
			//Cas ou le groupid n'est pas passe en parametre
			$cvSelectReq = "
				SELECT c.*
				FROM netcv_resumes_by_lang c
				WHERE id = '".mysql_escape_string($cvid)."' 
				AND group_id IN (SELECT g.id
				FROM netcv_resumes_group g, netcv_users u
				WHERE u.id = '".mysql_escape_string($userid)."')";
			$cvSelectRes = $this->db->prepare($cvSelectReq);
			$cvSelectRes->execute();
		} else {
			//Selection du single cv de l'utilisateur
			$cvSelectReq = "
				SELECT c.*
				FROM netcv_resumes_by_lang c, netcv_resumes_group g
				WHERE c.group_id = '".mysql_escape_string($groupid)."'
				AND g.id = c.group_id
				AND c.id = '".mysql_escape_string($cvid)."'
				AND g.user_id = '".mysql_escape_string($userid)."'";
			$cvSelectRes = $this->db->prepare($cvSelectReq);
			$cvSelectRes->execute();
		}

		if ( $cvSelectRows = $cvSelectRes->fetchAll(PDO::FETCH_ASSOC)) {
			$this->infos = $cvSelectRows[0];
		}
	}
	
	function updateInfos ($infos) {
				$setValues = "";
				foreach ($infos as $key => $value) {
					$setValues .= $key." = '".$value."',"; 
				}
		
				$updatePersonalInfoReq = "
					UPDATE netcv_resumes_by_lang 
					SET
						".$setValues."
						date_modification	= '".date("Y-m-d H:i:s")."'
					WHERE id='".$this->getInfo("id")."'
					AND group_id='".$this->getInfo("group_id")."'";
				$updatePersonalInfoRes = $this->db->prepare($updatePersonalInfoReq);
				$updatePersonalInfoRes->execute();
	}
	
	function countSections () {
		if (isset($this->content)) {
			return count($this->content->child);
		} else {
			return FALSE;
		}
	}
	
	function countElements () {
		$total = 0;
		if (isset($this->content)) {
			if (count($this->content->child)>0) {
				foreach ($this->content->child as $section) {
					$total += count($section->child);
				}
				return $total;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}		
	}
}

?>