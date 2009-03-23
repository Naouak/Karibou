<?php 

/**
 * @copyright 2008 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

include_once(dirname(__FILE__) . "/smbclient.class.php");

class MyFiles extends Model
{
	private function getSmbClient () {
		$loginName = $this->currentUser->getLogin();
		if (preg_match('/^([a-zA-Z0-9\.\-_]+)@([a-zA-Z0-9\.\-_]+)$/', $loginName, $match)) {
			$loginName = $match[1];
		}
		$passwd = "";
		$keychain = KeyChainFactory::getKeyChain($this->currentUser);
		if ($keychain !== FALSE) {
			if ( $keychain->unlock() ) {
				$passwd = $keychain->get("session_password");
			}
		}
		
		if (!isset($_SESSION["homeDirectory"])) {
			/* Bind to the ldap, and grab the home directory */
					$ldapconn = ldap_connect($GLOBALS['config']["myfiles"]["ADServer"]);
			$ldaprdn = "CN=" . $loginName . "," . $GLOBALS['config']["myfiles"]["bindDN"];
			if ($ldapconn) {
				$ldapbind = ldap_bind($ldapconn, $ldaprdn, $passwd);
				// verify binding
				if ($ldapbind) {
					$searchResult = ldap_search($ldapconn, $GLOBALS['config']["myfiles"]["searchDN"], "(CN=$loginName)");  
					$info = ldap_get_entries($ldapconn, $searchResult);
					$_SESSION["homeDirectory"] = $info[0]["homedirectory"][0];
				}
				ldap_close($ldapconn);
			}
		}
		$homeDirectory = $_SESSION["homeDirectory"];
		if (substr($homeDirectory, 0, 3) == "i:\\") {
			$homeDirectory = str_replace("i:\\", "\\\\eleve3\\", $homeDirectory);
			$_SESSION["homeDirectory"] = $homeDirectory;
		}
		return new SmbClient($homeDirectory, $loginName, $passwd);
	}
	
	public function build()
	{
		$folder = "/";
		if (isset($_POST["folder"]))
			$folder = substr(escapeshellarg(base64_decode($_POST["folder"])), 1, strlen(escapeshellarg(base64_decode($_POST["folder"]))) - 2);
		if ((strpos($folder, "..")) || (strpos($folder, "`"))) {
			die("Invalid path.");
		}
		
		$smb = $this->getSmbClient();
		
		if (isset($_GET["fileName"])) {
			$fileName = substr(escapeshellarg(base64_decode($_GET["fileName"])), 1, strlen(escapeshellarg(base64_decode($_GET["fileName"]))) - 2);
			if ((strpos($fileName, "..")) || (strpos($fileName, "`"))) {
				die("Invalid path.");
			}
			
			$tmpFile = $smb->download($fileName);
			
// 			header("Content-type: application/octet-stream");
			header("Content-disposition: attachment; filename=\"".basename($fileName)."\"");
			header("Content-Type: application/force-download");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: " . filesize($tmpFile));
			header("Pragma: no-cache");
			header("Expires: 0");
			readfile($tmpFile);
			unlink($tmpFile);
			exit;
		}
		
		$entries = $smb->ls($folder);
		$displayEntries = array();
		foreach ($entries as $entry) {
			if ($entry->isFolder()) {
				if ($entry->getName() != ".") {
					$displayEntries[] = $entry;
				}
			}
		}
		foreach ($entries as $entry) {
			if (!($entry->isFolder())) {
				$displayEntries[] = $entry;
			}
		}
		$this->assign("fileEntries", $displayEntries);
	}

}

?>
