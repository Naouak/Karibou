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
class IlsontditModify extends Model {
    public function build() {
        $stmt = $this->db->prepare("select * from ilsontdit where id=:id;");
        $stmt->bindParam(":id",intval($this->args["id"]),PDO::PARAM_INT);
        try{
            $stmt->execute();
        }
        catch(PDOException $e) {
            Debug::kill($e->getMessage());
        }
        $ilsontdit = $stmt->fetchAll();
        $this->assign("tomodify",$ilsontdit);
        $this->assign("currentuser",$this->currentUser->getId());
        $this->assign("isadmin", $this->getPermission() == _ADMIN_);
    }
}

