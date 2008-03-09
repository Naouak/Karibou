<?php
/**
 * @copyright 2008 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package framework
 **/

ClassLoader::add('AuthMYSQL', dirname(__FILE__).'/authMYSQL.class.php');
ClassLoader::add('AuthLDAP', dirname(__FILE__).'/authLDAP.class.php');
ClassLoader::add('AuthPAM', dirname(__FILE__).'/authPAM.class.php');


/**
 * Used to control the login and password of users
 * 
 * @package framework
 */

class AuthManager
{
	private $authModules;
	private $notifiedModules;
	
	public function __construct() {
		$this->authModules = array();
		$this->notifiedModules = array();
		
		foreach ($GLOBALS["config"]["login"]["loginBackends"] as $backend) {
			$module = "Auth" . strtoupper($backend["module"]);
			$authObject = new $module($backend);
			if (isset($backend["notify"]) && ($backend["notify"]))
				$this->notifiedModules[] = $authObject;
			$this->authModules[] = $authObject;
		}
	}
	
	public function checkPassword ($login, $password) {
		foreach ($this->authModules as $module) {
			if ($module->check($login, $password)) {
				foreach ($this->notifiedModules as $notify) {
					if ($notify != $module) {
						$notify->notify($login, $password);
					}
				}
				return true;
			}
		}
		return false;
	}
	
	public function changePassword ($login, $old, $new) {
		$result = false;
		foreach ($this->authModules as $module) {
			if ($module->change($login, $old, $new))
				$result = true;
		}
		return $result;
	}
}

?>