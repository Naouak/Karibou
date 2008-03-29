<?php 

/**
 * @version $Id $
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
        $query = "SELECT u.id, YEAR(p.birthday) AS birthYear from ".$frameworkdb.".profile p, ".$frameworkdb.".users u where MONTH(p.birthday) = MONTH(CURRENT_DATE()) AND DAY(p.birthday) = DAY(CURRENT_DATE()) and u.profile_id=p.id";	
        $bdayers = Array();
        foreach($this->db->query($query) as $row) {
            $user['user'] = $this->userFactory->prepareUserFromId($row['id']);
            $user['age'] =  date("Y") - $row["birthYear"];
            $bdayers[] = $user;
        }
        $this->assign('bdayers', $bdayers);
    }
}

?>
