<?php

class MiniApp {
	/**
	 * Contains the translated names of the application
	 */
	private $names = array();
	/**
	 * Contains the translated descriptions of the application
	 */
	private $descs = array();
	
	private $appName, $mainView, $jsView, $configView;
	
	public function __construct($appName, $parameters) {
		$this->appName = $appName;
		$this->names = $parameters["names"];
		if (count($this->names) == 0)
			throw new Exception("Missing parameter names for MiniApp $appName");
		$this->descs = $parameters["descs"];
		if (count($this->descs) == 0)
			throw new Exception("Missing parameter descs for MiniApp $appName");
		$this->mainView = $parameters["view"];
		if (strlen($this->mainView) < 1)
			throw new Exception("Missing parameter view for MiniApp $appName");
		if (array_key_exists("configview", $parameters))
			$this->configView = $parameters["configview"];
		else
			$this->configView = "";
		if (array_key_exists("JSview", $parameters))
			$this->jsView = $parameters["JSview"];
		else
			$this->jsView = "";
	}
	
	public function getAppName() {
		return $this->appName;
	}
	
	public function getMainView() {
		return $this->mainView;
	}
	
	public function getConfigView() {
		return $this->configView;
	}
	
	public function getJsView() {
		return $this->jsView;
	}
	
	public function getName($language) {
		if (array_key_exists($language, $this->names))
			return $this->names[$language];
		return $this->appName;
	}
	
	public function getDesc($language) {
		if (array_key_exists($language, $this->descs))
			return $this->descs[$language];
		return "";
	}
	
}

?>