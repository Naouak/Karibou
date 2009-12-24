<?php

class SmbFileEntry {
	private $fileName;
	private $folder;
	private $fileSize;
	private $fileDate;
	
	private $fullPath;
	
	public function __construct ($smbLine, $folder) {
		$attributes = trim(substr($smbLine, strlen($smbLine) - 42, 7));
		$this->fileName = trim(substr($smbLine, 0, strlen($smbLine) - 42));
		if (ereg("D", $attributes)) {
			$this->folder = true;
		} else {
			$this->folder = false;
		}
		$this->fileSize = trim(substr($smbLine, strlen($smbLine) - 35, 10));
		$this->fileDate = trim(substr($smbLine, strlen($smbLine) - 24, 24));
		if ($this->fileName == ".") {
			$this->fullPath = substr($folder, -1);
		} else if ($this->fileName == "..") {
			$_folder = $folder;
			while (substr($_folder, -1) == '/')
				$_folder = substr($_folder, 0, strlen($_folder) - 2);
			$this->fullPath = substr($_folder, 0, strrpos($_folder, "/"));
		} else {
			$this->fullPath = $folder . $this->fileName;
		}
	}

	public function getName () {
		return $this->fileName;
	}
	
	public function isFolder () {
		return $this->folder;
	}
	
	public function getSize () {
		return $this->fileSize;
	}
	
	public function getDate () {
		return $this->fileDate;
	}
	
	public function getFullPath () {
		return $this->fullPath;
	}

	public function getFullPathBase64() {
		return base64_encode($this->fullPath);
	}
}

class SmbClient {
	private $serverPath;
	private $homePath;
	private $loginName;
	private $password;
	
	public function __construct ($homeDirectory, $loginName, $password) {
		// Split the homeDirectory to serverPath and homePath
		$blocs = explode("\\", $homeDirectory);
		$this->serverPath = "\\\\" . $blocs[2] . "\\" . $blocs[3];
		$this->homePath = str_replace("\\", "/", substr($homeDirectory, strlen($this->serverPath)));
		if (substr($this->homePath, -1) != '/')
			$this->homePath .= '/';
		$this->password = $password;
		$this->loginName = $loginName;
	}
	
	public function ls ($folder) {
		$entries = array();
		$smbCommand = "smbclient " . self::estr($this->serverPath) . "  -D " . self::estr($this->homePath . $folder) . " -U " . self::estr($this->loginName . "%" . $this->password) . " -c \"ls\"";
		exec($smbCommand, $lines);
		if ((substr($folder, -1) != '/') && ($folder != ""))
			$folder .= '/';
		foreach ($lines as $line) {
			if (strlen(trim($line)) == 0)
				break;
			$entries[] = new SmbFileEntry($line, $folder);
		}
		return $entries;
	}
	
	public function download ($fileName) {
		$temp = tempnam("/tmp", "SMB");
		if (substr($fileName, 0, 1) == "/")
			$fileName = substr($fileName, 1);
		$fileName = str_replace("/", "\\", $fileName);
		$smbCommand = "smbclient " . self::estr($this->serverPath) . "  -D " . self::estr($this->homePath) . " -U " . self::estr($this->loginName . "%" . $this->password) . " -c ";
		$smbCommand .= self::estr('get "' . addcslashes($fileName, '"') . '" "' . addcslashes($temp, '"') . '"');
		exec($smbCommand, $lines);
		return $temp;
	}

	private static function estr($str) {
		return "'" . str_replace("'", "'\"'\"'", $str) . "'";
	}
}
