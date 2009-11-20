<?php
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *-
 * @package applications
 **/


/**
 * Classe commentaires
 *
 * @package applications
 */

class Commentaires extends Model {
    public function build() {
        $combox = new CommentSource($this->db,$this->args["id"]);
        // we get all old comments for this couple appname,id
        $sqlc = $this->db->prepare("select * from comment  where `key_id`=:id and deleted=0");
        $sqlc->bindValue(":id", $this->args["id"]);
        $sqlc->execute();
        $this->assign("name",$combox->getName());
        $this->assign("title",$combox->getTitle());
        $this->assign("content",$combox->getContent());
        $this->assign("id",$combox->getId());
        $existing = $sqlc->fetchAll();
        $coms = Array();
        foreach($existing as $k => $v){
            $v['user'] = $this->userFactory->prepareUserFromId($v['user']);
            $coms[] = $v;
        }
        $this->assign("existing", $coms);
    }
}
