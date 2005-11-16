<?php

class NetCVSkin {
	var $infos;
	
	function __construct($infos = FALSE) {
		if ($infos != FALSE) {
			$this->infos = $infos;
		}
	}
	
	function getInfo ($key) {
		if (isset($this->infos[$key])) {
			return $this->infos[$key];
		} else {
			return FALSE;
		}
	}
	
}

?>