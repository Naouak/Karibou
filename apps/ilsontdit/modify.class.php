<?php

/**
 * @copyright 2009 Gilles DEHAUDT
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *-
 * @package applications
 **/

class modify extends FormModel {
    public function build() {
        $group = filter_input(INPUT_POST,"group",FILTER_SANITIZE_SPECIAL_CHARS);
        $who = filter_input(INPUT_POST,"who",FILTER_SANITIZE_SPECIAL_CHARS);
        $what = filter_input(INPUT_POST,"what",FILTER_SANITIZE_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST,"id",FILTER_SANITIZE_NUMBER_INT);
        
        $stmt = $this->db->prepare("UPDATE ilsontdit  SET `group`=:group, `who`=:who, `message`=:what WHERE `id`=:id ;");
        $stmt->bindValue(":id",$id);
        $stmt->bindValue(":who",$who);
        $stmt->bindValue(":what",$what);
        $stmt->bindValue(":group",$group);
        try{
            $stmt->execute();
        }
        catch(PDOException $e){
            Debug::kill($e->getMessage());
        }
    }
}
