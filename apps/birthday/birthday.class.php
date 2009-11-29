<?php 

/**
 * @copyright 2009 Pierre Ducroquet <pinaraf@gmail.com>
 * @copyright 2007 Antoine Reversat
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class Birthday extends Model
{
	public function build()
	{
		$frameworkdb = $GLOBALS['config']['bdd']['frameworkdb'];
		$query = "SELECT u.id, YEAR(CURRENT_DATE())-YEAR(p.birthday) AS age
			FROM $frameworkdb.profile p
			INNER JOIN $frameworkdb.users u ON u.profile_id=p.id
			WHERE MONTH(p.birthday)=MONTH(CURRENT_DATE()) AND DAY(p.birthday)=DAY(CURRENT_DATE())";
		$bdayers = Array();
		foreach($this->db->query($query) as $row) {
			$user['user'] = $this->userFactory->prepareUserFromId($row['id']);
			$user['age'] = $row["age"];
			$bdayers[] = $user;
		}
		$this->assign('bdayers', $bdayers);
	}
}

?>
