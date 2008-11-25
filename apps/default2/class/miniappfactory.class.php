<?php

/***
 * .app2 file attributes :
 * name_fr
 * name_en
 * desc_fr
 * desc_en
 * view
 * JSview
 * configview
 **/

class MiniAppFactory {
	private static $appsCache = array();

	private static function initAppCache () {
		if (count(self::$appsCache) > 0)
			return;
		if ($dh = opendir(KARIBOU_APP_DIR)) {
			while (($file = readdir($dh)) !== false) {
				$fullName = KARIBOU_APP_DIR . '/' . $file;
				if (($file[0] != '.') && (is_dir($fullName)) && (is_file($fullName . "/$file.app2"))) {
					$appFile = "$fullName/$file.app2";
					$attributes = parse_ini_file($appFile);
					$names = array();
					$descs = array();
					$app = array();
					foreach ($attributes as $key => $value) {
						if ((strpos($key, "name_") === 0) && (strlen($key) != 5))
							$names[substr($key, 5)] = $value;
						else if ((strpos($key, "desc_") === 0) && (strlen($key) != 5))
							$descs[substr($key, 5)] = $value;
						else if (($key == "view") || ($key == "JSview") || ($key == "configview"))
							$app[$key] = $value;
					}
					$app["names"] = $names;
					$app["descs"] = $descs;
					self::$appsCache[$file] = $app;
				}
			}
		}
	}
	
	public static function buildApplication ($name) {
		MiniAppFactory::initAppCache();
		if (array_key_exists($name, MiniAppFactory::$appsCache)) {
			return new MiniApp($name, MiniAppFactory::$appsCache[$name]);
		} else {
			return null;
		}
	}
	
	public static function listApplications () {
		MiniAppFactory::initAppCache();
		return array_keys(self::$appsCache);
	}
}

?>