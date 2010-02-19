<?php

/**
 * @copyright 2009 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/


class Theme {
	private $name;

	private $description;

	private $valid = false;

	private $parentname = null;

	private $parent = null;

	private $hidden = false;

	public function __construct ($name) {
		$this->name = $name;
		$settings = KARIBOU_THEMES_DIR . '/' . $name . '/theme.config';
		if (is_file($settings)) {
			$attrs = parse_ini_file($settings);
			$this->description = $attrs["description"];
			$this->hidden = ($attrs["hidden"] == "true");
			if (array_key_exists("parent", $attrs))
				$this->parentname = $attrs["parent"];
			$this->valid = true;
		}
	}

	public function isValid() {
		return $this->valid;
	}

	public function getCSS($appname, $fallback) {
		if (($this->parent == null) && ($this->parentname != null))
			$this->parent = ThemeManager::instance()->getTheme($this->parentname);
		$filename = '/' . $this->name . '/' . $appname . '.css';

		$base = array();
		if ($this->parent != null)
			$base = $this->parent->getCSS($appname, $fallback);
		if (is_file(KARIBOU_THEMES_DIR . $filename))
			return array_merge($base, array($filename));
		else if (($fallback != null) && (count($base) == 0))
			return $fallback->getCSS($appname, null);
		return $base;
	}

	public function getName() {
		return $this->name;
	}

	public function getDescription() {
		return $this->description;
	}

	public function isHidden() {
		return $this->hidden;
	}
}

class ThemeManager {

	/**
	 * @var ThemeManager $instance : the singleton instance
	 */
	private static $instance = null;

	private $themes = array();

	private $applications = array("core");

	public static function instance() {
		if (ThemeManager::$instance == null)
			ThemeManager::$instance = new ThemeManager();
		return ThemeManager::$instance;
	}

	private function __construct() {
		if ($handle = opendir(KARIBOU_THEMES_DIR)) {
			while (($file = readdir($handle)) !== false) {
				if (!is_dir(KARIBOU_THEMES_DIR . '/' . $file))
					continue;
				if ($file[0] == '.')
					continue;
				$theme = new Theme($file);
				if ($theme->isValid())
					$this->themes[$file] = $theme;
			}
		}
		if (count($this->themes) == 0)
			die("No theme found !");
	}

	/**
	 * Add an application to the list of CSS that will have to be loaded.
	 * @param string $appName : name of the application that is going to be added.
	 */
	public function addApplication($appName) {
		$this->applications[] = $appName;
	}

	public function flushApplications() {
		$this->applications = array();
	}

	public function getVisibleThemes() {
		$result = array();
		foreach ($this->themes as $name => $theme) {
			if (($theme->isValid()) && !($theme->isHidden())) {
				$result[$name] = $theme;
			}
		}
		return $result;
	}

	public function getTheme($name) {
		if (array_key_exists($name, $this->themes))
			return $this->themes[$name];
		return null;
	}
	/**
	 * Get all the CSS needed for the currently loaded applications.
	 */
	public function getCSSList() {
		$result = array();
		$currentUser = UserFactory::instance()->getCurrentUser();
		
		$core = $this->getTheme("core");
		$themename = "";
		if ($currentUser->isLogged())
			$themename = $currentUser->getPref("theme");
		if (!$themename)
			$themename = $GLOBALS["config"]["style"];
		$fallback = $this->getTheme($GLOBALS["config"]["style"]);
		$theme = $this->getTheme($themename);

		foreach ($this->applications as $app) {
			$csss = $core->getCSS($app);
			foreach($csss as $css)
				$result[] = KARIBOU_THEMES_URL . $css;

			$csss = $theme->getCSS($app, $fallback);
			foreach($csss as $css)
				$result[] = KARIBOU_THEMES_URL . $css;
		}
		return $result;
	}
}
