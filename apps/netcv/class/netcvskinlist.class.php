<?php

class NetCVSkinList {
	protected $skins;
	protected $db;

	function __construct ($db) {
		$this->db = $db;
		
		$this->skins = array();
	
		$skinsSelectReq = "SELECT * FROM netcv_skins WHERE display = '1' ORDER BY `category`";
		$skinsSelectRes = $this->db->prepare($skinsSelectReq);
		$skinsSelectRes->execute();
		
		$allskins = $skinsSelectRes->fetchAll();
		foreach ($allskins as $skin) {
			$this->skins[] = new NetCVSkin ($skin);
		}
	}

	function getSkinList() {
		return $this->skins;
	}	
	
}

?>