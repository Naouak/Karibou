<?php
/**
 * @copyright 2008 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package framework
 **/

/**
 * Used to check log-in of an user using a MySQL database
 *
 * @package framework
 */

class AuthMYSQL extends Auth
{
	private $db;
	
	function __construct ($params) {
		$this->db = new PDO($params["dsn"], $params["username"], $params["password"]);
	}
	
	/**
	 * @param String $user
	 * @param String $pass
	*/
	function check($user, $pass)
	{
		$qry = $this->db->prepare("SELECT COUNT(id) FROM users WHERE login=:login AND password=PASSWORD(:passwd)");
		$qry->bindValue(":login", $user);
		$qry->bindValue(":passwd", $pass);
		if ($qry->execute()) {
			if ($row = $qry->fetch()) {
				if ($row[0] == 1) {
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	 * @param String $user
	 * @param String $old_password
	 * @param String $new_password
	 */
	function change ($user, $old_password, $new_password)
	{
		$qry = $this->db->prepare("UPDATE users SET password=PASSWORD(:newpasswd) WHERE login=:login AND password=PASSWORD(:oldpasswd)");
		$qry->bindValue(":login", $user);
		$qry->bindValue(":newpasswd", $new_password);
		$qry->bindValue(":oldpasswd", $old_password);
		if ($qry->execute()) {
			if ($row = $qry->fetch()) {
				if ($row[0] == 1) {
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	* @param String $user
	* @param String $password
	*/
	function notify ($user, $password)
	{
		$qry = $this->db->prepare("UPDATE users SET password=PASSWORD(:passwd) WHERE login=:login");
		$qry->bindValue(":login", $user);
		$qry->bindValue(":passwd", $password);
		if ($qry->execute()) {
			if ($row = $qry->fetch()) {
				if ($row[0] == 1) {
					return true;
				}
			}
		}
		return false;
	}
}

?>
