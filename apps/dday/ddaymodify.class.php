<?php
/**
 * @copyright 2009 Gilles DEHAUDT
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *-
 * @package applications
 **/

/**-
 *-
 * @package applications
 **/
class DDayModify extends Model {
    public function build() {
        $stmt=$this->db->prepare("SELECT * from dday where id=:id;");
        $stmt->bindValue(":id", $this->args["id"]);
        try{
            $stmt->execute();
        }
        catch(PDOException $e){
            Debug::kill($e->getMessage());
        }
        $data=$stmt->fetchAll();
        $this->assign("dday",$data);
        $this->assign("currentuser",$this->currentUser->getId());
        $this->assign("isadmin", $this->getPermission() == _ADMIN_);
    }
}
