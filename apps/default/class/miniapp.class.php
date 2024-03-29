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
	
	private $appName, $mainView, $jsView, $configModel, $submitModel, $historyPage;
	
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
		if (array_key_exists("configmodel", $parameters))
			$this->configModel = $parameters["configmodel"];
		else
			$this->configModel = "";
		if (array_key_exists("JSview", $parameters)) {
			$this->jsView = $parameters["JSview"];
		} else {
			$this->jsView = "";
		}
		if (array_key_exists("submitmodel", $parameters))
			$this->submitModel = $parameters["submitmodel"];
		else
			$this->submitModel = "";
		if (array_key_exists("history", $parameters))
			$this->historyPage = $parameters["history"];
		else
			$this->historyPage = null;
	}
	
	public function getAppName() {
		return $this->appName;
	}
	
	public function getMainView() {
		return $this->mainView;
	}
	
	public function getConfigModel() {
		return $this->configModel;
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

	public function getSubmitModel() {
		return $this->submitModel;
	}

	public function getHistoryPage() {
		return $this->historyPage;
	}
}

?>
